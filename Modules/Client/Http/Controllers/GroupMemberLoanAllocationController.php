<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Client\Entities\Group;
use Modules\Client\Entities\GroupMemberLoanAllocation;
use Modules\Loan\Entities\Loan;
use Yajra\DataTables\Facades\DataTables;

class GroupMemberLoanAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:client.groups.view'])->only(['index', 'show', 'getLoanAllocations']);
        $this->middleware(['permission:client.groups.manage_members'])->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display loan allocations for a specific loan
     */
    public function index($loanId)
    {
        $loan = Loan::with(['group', 'memberAllocations.client'])->findOrFail($loanId);
        
        if ($loan->client_type !== 'group') {
            abort(404, 'This is not a group loan');
        }

        return theme_view('client::group_loan_allocation.index', compact('loan'));
    }

    /**
     * Show the form for creating member allocations
     */
    public function create($loanId)
    {
        $loan = Loan::with(['group.active_members'])->findOrFail($loanId);
        
        if ($loan->client_type !== 'group') {
            abort(404, 'This is not a group loan');
        }

        // Check if allocations already exist
        if ($loan->memberAllocations()->exists()) {
            return redirect()->route('loan.member-allocations.edit', $loanId);
        }

        return theme_view('client::group_loan_allocation.create', compact('loan'));
    }

    /**
     * Store member allocations
     */
    public function store(Request $request, $loanId)
    {
        $loan = Loan::with('group.active_members')->findOrFail($loanId);
        
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*.client_id' => 'required|exists:clients,id',
            'allocations.*.allocated_amount' => 'required|numeric|min:0',
            'allocations.*.allocated_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Validate total percentage equals 100%
        $totalPercentage = collect($request->allocations)->sum('allocated_percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return back()->withErrors(['allocations' => 'Total allocation percentage must equal 100%']);
        }

        // Validate total amount equals loan principal
        $totalAmount = collect($request->allocations)->sum('allocated_amount');
        if (abs($totalAmount - $loan->principal) > 0.01) {
            return back()->withErrors(['allocations' => 'Total allocated amount must equal loan principal']);
        }

        // Create allocations
        foreach ($request->allocations as $allocation) {
            $memberAllocation = GroupMemberLoanAllocation::create([
                'loan_id' => $loan->id,
                'group_id' => $loan->group_id,
                'client_id' => $allocation['client_id'],
                'allocated_amount' => $allocation['allocated_amount'],
                'allocated_percentage' => $allocation['allocated_percentage'],
                'outstanding_balance' => $allocation['allocated_amount'],
                'status' => 'active',
                'notes' => $allocation['notes'] ?? null,
            ]);
            
            // Generate payment schedules for this allocation
            $memberAllocation->generatePaymentSchedules();
        }

        activity()->on($loan)
            ->withProperties(['loan_id' => $loan->id, 'group_id' => $loan->group_id])
            ->log('Create Group Loan Member Allocations');

        \flash('Member allocations created successfully')->success()->important();
        return redirect()->route('loan.member-allocations.index', $loanId);
    }

    /**
     * Show member allocation details
     */
    public function show($loanId, $allocationId)
    {
        $allocation = GroupMemberLoanAllocation::with(['loan', 'group', 'client'])->findOrFail($allocationId);
        
        return theme_view('client::group_loan_allocation.show', compact('allocation'));
    }

    /**
     * Show the form for editing member allocations
     */
    public function edit($loanId)
    {
        $loan = Loan::with(['group.active_members', 'memberAllocations.client'])->findOrFail($loanId);
        
        if ($loan->client_type !== 'group') {
            abort(404, 'This is not a group loan');
        }

        return theme_view('client::group_loan_allocation.edit', compact('loan'));
    }

    /**
     * Update member allocations
     */
    public function update(Request $request, $loanId)
    {
        $loan = Loan::with('memberAllocations')->findOrFail($loanId);
        
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*.id' => 'required|exists:group_member_loan_allocations,id',
            'allocations.*.allocated_amount' => 'required|numeric|min:0',
            'allocations.*.allocated_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Validate total percentage equals 100%
        $totalPercentage = collect($request->allocations)->sum('allocated_percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return back()->withErrors(['allocations' => 'Total allocation percentage must equal 100%']);
        }

        // Update allocations
        foreach ($request->allocations as $allocationData) {
            $allocation = GroupMemberLoanAllocation::findOrFail($allocationData['id']);
            $allocation->update([
                'allocated_amount' => $allocationData['allocated_amount'],
                'allocated_percentage' => $allocationData['allocated_percentage'],
                'notes' => $allocationData['notes'] ?? $allocation->notes,
            ]);
            $allocation->calculateOutstandingBalance();
            $allocation->save();
        }

        activity()->on($loan)
            ->withProperties(['loan_id' => $loan->id, 'group_id' => $loan->group_id])
            ->log('Update Group Loan Member Allocations');

        \flash('Member allocations updated successfully')->success()->important();
        return redirect()->route('loan.member-allocations.index', $loanId);
    }

    /**
     * Get loan allocations for DataTables
     */
    public function getLoanAllocations($loanId)
    {
        $allocations = GroupMemberLoanAllocation::with(['client', 'group'])
            ->where('loan_id', $loanId)
            ->select('group_member_loan_allocations.*');

        return DataTables::of($allocations)
            ->editColumn('client', function ($allocation) {
                return $allocation->client->first_name . ' ' . $allocation->client->last_name;
            })
            ->editColumn('allocated_amount', function ($allocation) {
                return number_format($allocation->allocated_amount, 2);
            })
            ->editColumn('allocated_percentage', function ($allocation) {
                return $allocation->allocated_percentage . '%';
            })
            ->editColumn('total_paid', function ($allocation) {
                return number_format($allocation->total_paid, 2);
            })
            ->editColumn('outstanding_balance', function ($allocation) {
                return number_format($allocation->outstanding_balance, 2);
            })
            ->editColumn('payment_percentage', function ($allocation) {
                return $allocation->payment_percentage . '%';
            })
            ->editColumn('status', function ($allocation) {
                $class = $allocation->status === 'active' ? 'success' : 
                        ($allocation->status === 'completed' ? 'primary' : 'danger');
                return '<span class="badge badge-' . $class . '">' . ucfirst($allocation->status) . '</span>';
            })
            ->addColumn('action', function ($allocation) {
                return '<a href="' . route('loan.member-allocations.show', [$allocation->loan_id, $allocation->id]) . '" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> View
                </a>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Record a payment for a specific member allocation
     */
    public function recordPayment(Request $request, $loanId, $allocationId)
    {
        $allocation = GroupMemberLoanAllocation::with('paymentSchedules')->findOrFail($allocationId);
        
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $paymentAmount = $request->payment_amount;
        $remainingPayment = $paymentAmount;
        $paymentDate = $request->payment_date;
        
        // Get unpaid installments in order
        $unpaidInstallments = $allocation->paymentSchedules()
            ->where('status', '!=', 'paid')
            ->orderBy('installment_number')
            ->get();

        if ($unpaidInstallments->isEmpty()) {
            \flash('No outstanding installments found for this member')->error()->important();
            return redirect()->back();
        }

        // Process payment across installments
        foreach ($unpaidInstallments as $installment) {
            if ($remainingPayment <= 0) break;
            
            $installmentDue = $installment->outstanding_balance;
            $paymentForThisInstallment = min($remainingPayment, $installmentDue);
            
            if ($paymentForThisInstallment > 0) {
                $excessPayment = $installment->processPayment($paymentForThisInstallment, $paymentDate);
                $installment->save();
                
                $remainingPayment -= $paymentForThisInstallment;
                
                // If there's excess payment, add it to remaining payment for next installment
                if ($excessPayment > 0) {
                    $remainingPayment += $excessPayment;
                }
            }
        }

        // Check for insufficient payment (defaulted status)
        $totalDueForCurrentInstallment = $unpaidInstallments->first()->total_due ?? 0;
        if ($paymentAmount < $totalDueForCurrentInstallment && $unpaidInstallments->first()->due_date < now()) {
            $unpaidInstallments->first()->markAsDefaulted('Insufficient payment: ' . number_format($paymentAmount, 2) . ' paid, ' . number_format($totalDueForCurrentInstallment, 2) . ' required');
            \flash('Payment recorded but marked as defaulted due to insufficient amount')->warning()->important();
        }

        // Update allocation totals
        $allocation->principal_paid = $allocation->paymentSchedules->sum('principal_paid');
        $allocation->interest_paid = $allocation->paymentSchedules->sum('interest_paid');
        $allocation->fees_paid = $allocation->paymentSchedules->sum('fees_paid');
        $allocation->penalties_paid = $allocation->paymentSchedules->sum('penalties_paid');
        $allocation->updateTotalPaid();
        
        // Update allocation status
        if ($allocation->paymentSchedules()->where('status', '!=', 'paid')->count() == 0) {
            $allocation->status = 'completed';
        } elseif ($allocation->paymentSchedules()->where('status', 'defaulted')->exists()) {
            $allocation->status = 'defaulted';
        }
        
        $allocation->save();

        activity()->on($allocation)
            ->withProperties([
                'allocation_id' => $allocation->id,
                'payment_amount' => $paymentAmount,
                'remaining_payment' => $remainingPayment,
                'payment_date' => $paymentDate
            ])
            ->log('Record Group Member Loan Payment');

        if ($remainingPayment > 0) {
            \flash('Payment recorded successfully. Excess payment of ' . number_format($remainingPayment, 2) . ' applied to future installments')->success()->important();
        } else {
            \flash('Payment recorded successfully')->success()->important();
        }
        
        return redirect()->back();
    }

    /**
     * Get next installment details for payment
     */
    public function getNextInstallment($loanId, $allocationId)
    {
        $allocation = GroupMemberLoanAllocation::findOrFail($allocationId);
        $nextInstallment = $allocation->getNextUnpaidInstallment();
        
        if (!$nextInstallment) {
            return response()->json(['error' => 'No unpaid installments found'], 404);
        }
        
        return response()->json([
            'installment_number' => $nextInstallment->installment_number,
            'due_date' => $nextInstallment->due_date->format('Y-m-d'),
            'principal_due' => $nextInstallment->principal_due - $nextInstallment->principal_paid,
            'interest_due' => $nextInstallment->interest_due - $nextInstallment->interest_paid,
            'fees_due' => $nextInstallment->fees_due - $nextInstallment->fees_paid,
            'penalties_due' => $nextInstallment->penalties_due - $nextInstallment->penalties_paid,
            'total_due' => $nextInstallment->outstanding_balance,
            'is_overdue' => $nextInstallment->isOverdue()
        ]);
    }
}
