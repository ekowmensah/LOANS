<?php

namespace Modules\FieldAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\FieldAgent\Entities\FieldCollection;
use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;
use Modules\Loan\Entities\Loan;
use Yajra\DataTables\Facades\DataTables;

class FieldCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:field_agent.collections.index'])->only(['index', 'get_collections']);
        $this->middleware(['permission:field_agent.collections.create'])->only(['create', 'store']);
        $this->middleware(['permission:field_agent.collections.view'])->only(['show']);
        $this->middleware(['permission:field_agent.collections.verify'])->only(['verify_index', 'verify', 'reject']);
        $this->middleware(['permission:field_agent.collections.post'])->only(['post']);
    }

    /**
     * Display a listing of collections
     */
    public function index()
    {
        $fieldAgents = FieldAgent::active()->get();
        return theme_view('fieldagent::collection.index', compact('fieldAgents'));
    }

    /**
     * Get collections data for DataTables
     */
    public function get_collections(Request $request)
    {
        $query = FieldCollection::with(['fieldAgent.user', 'client']);

        // Filter by field agent
        if ($request->field_agent_id) {
            $query->where('field_agent_id', $request->field_agent_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by collection type
        if ($request->collection_type) {
            $query->where('collection_type', $request->collection_type);
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('collection_date', [$request->start_date, $request->end_date]);
        }

        // If user is a field agent, show only their collections
        $user = Auth::user();
        if ($user->can('field_agent.collections.view_own') && !$user->can('field_agent.collections.index')) {
            $fieldAgent = FieldAgent::where('user_id', $user->id)->first();
            if ($fieldAgent) {
                $query->where('field_agent_id', $fieldAgent->id);
            }
        }

        return DataTables::of($query)
            ->editColumn('receipt_number', function ($data) {
                return '<a href="' . url('field-agent/collection/' . $data->id . '/show') . '">' . $data->receipt_number . '</a>';
            })
            ->editColumn('field_agent', function ($data) {
                return $data->fieldAgent ? $data->fieldAgent->full_name : 'N/A';
            })
            ->editColumn('client', function ($data) {
                return $data->client ? $data->client->first_name . ' ' . $data->client->last_name : 'N/A';
            })
            ->editColumn('collection_type', function ($data) {
                return $data->collection_type_label;
            })
            ->editColumn('amount', function ($data) {
                return number_format($data->amount, 2);
            })
            ->editColumn('collection_date', function ($data) {
                return $data->collection_date->format('Y-m-d');
            })
            ->editColumn('status', function ($data) {
                return $data->status_badge;
            })
            ->addColumn('action', function ($data) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="' . url('field-agent/collection/' . $data->id . '/show') . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                
                if ($data->canBeVerified() && Auth::user()->can('field_agent.collections.verify')) {
                    $actions .= '<a href="' . url('field-agent/collection/' . $data->id . '/verify') . '" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Verify</a>';
                }
                
                if ($data->canBePosted() && Auth::user()->can('field_agent.collections.post')) {
                    $actions .= '<a href="' . url('field-agent/collection/' . $data->id . '/post') . '" class="btn btn-primary btn-sm confirm"><i class="fa fa-paper-plane"></i> Post</a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['receipt_number', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new collection
     */
    public function create()
    {
        $fieldAgents = FieldAgent::active()->get();
        $clients = Client::where('status', 'active')->get();

        return theme_view('fieldagent::collection.create', compact('fieldAgents', 'clients'));
    }

    /**
     * Get loan payment information (arrears + next payment)
     */
    public function get_loan_payment_info(Request $request)
    {
        $loanId = $request->loan_id;
        $clientId = $request->client_id;
        
        try {
            $loan = Loan::with('repayment_schedules')->findOrFail($loanId);
            
            $arrears = 0;
            $nextPayment = 0;
            $nextDueDate = null;
            
            // Check if this is a group loan - use member's actual allocated amounts
            if ($loan->client_type === 'group' && $clientId) {
                $allocation = \Modules\Client\Entities\GroupMemberLoanAllocation::where('loan_id', $loanId)
                    ->where('client_id', $clientId)
                    ->first();
                
                if (!$allocation) {
                    return response()->json(['success' => false, 'error' => 'Client is not a member of this group loan.'], 400);
                }
                
                // Use member's allocated principal and interest
                $memberPrincipal = $allocation->allocated_amount ?? 0;
                $memberInterest = $allocation->allocated_interest ?? 0;
                $memberPrincipalPaid = $allocation->principal_paid ?? 0;
                $memberInterestPaid = $allocation->interest_paid ?? 0;
                
                // Calculate outstanding amounts
                $principalOutstanding = $memberPrincipal - $memberPrincipalPaid;
                $interestOutstanding = $memberInterest - $memberInterestPaid;
                
                // Get number of schedules
                $totalSchedules = $loan->repayment_schedules()->count();
                $paidSchedules = 0;
                
                // Count how many schedules are fully paid
                foreach ($loan->repayment_schedules as $schedule) {
                    $schedulePaid = $schedule->principal_repaid_derived + $schedule->interest_repaid_derived;
                    $scheduleDue = $schedule->principal + $schedule->interest;
                    if ($schedulePaid >= $scheduleDue) {
                        $paidSchedules++;
                    }
                }
                
                $remainingSchedules = $totalSchedules - $paidSchedules;
                
                if ($remainingSchedules > 0) {
                    // Calculate per-installment amount for this member
                    $principalPerInstall = $principalOutstanding / $remainingSchedules;
                    $interestPerInstall = $interestOutstanding / $remainingSchedules;
                    
                    // Get overdue schedules count
                    $overdueCount = $loan->repayment_schedules()
                        ->where('due_date', '<', now())
                        ->where('principal_repaid_derived', '<', \DB::raw('principal'))
                        ->count();
                    
                    // Calculate arrears (overdue installments)
                    if ($overdueCount > 0) {
                        $arrears = ($principalPerInstall + $interestPerInstall) * $overdueCount;
                    }
                    
                    // Get next schedule
                    $nextSchedule = $loan->repayment_schedules()
                        ->where('due_date', '>=', now())
                        ->orderBy('due_date', 'asc')
                        ->first();
                    
                    if ($nextSchedule) {
                        $nextPayment = $principalPerInstall + $interestPerInstall;
                        $nextDueDate = $nextSchedule->due_date;
                    }
                }
            } else {
                // Individual loan - use actual schedule amounts
                $overdueSchedules = $loan->repayment_schedules()
                    ->where('due_date', '<', now())
                    ->get();
                
                foreach ($overdueSchedules as $schedule) {
                    $principal = $schedule->principal - $schedule->principal_waived_derived - $schedule->principal_written_off_derived - $schedule->principal_repaid_derived;
                    $interest = $schedule->interest - $schedule->interest_waived_derived - $schedule->interest_written_off_derived - $schedule->interest_repaid_derived;
                    $fees = $schedule->fees - $schedule->fees_waived_derived - $schedule->fees_written_off_derived - $schedule->fees_repaid_derived;
                    $penalties = $schedule->penalties - $schedule->penalties_waived_derived - $schedule->penalties_written_off_derived - $schedule->penalties_repaid_derived;
                    
                    $arrears += $principal + $interest + $fees + $penalties;
                }
                
                // Get next upcoming schedule
                $nextSchedule = $loan->repayment_schedules()
                    ->where('due_date', '>=', now())
                    ->orderBy('due_date', 'asc')
                    ->first();
                
                if ($nextSchedule) {
                    $principal = $nextSchedule->principal - $nextSchedule->principal_waived_derived - $nextSchedule->principal_written_off_derived - $nextSchedule->principal_repaid_derived;
                    $interest = $nextSchedule->interest - $nextSchedule->interest_waived_derived - $nextSchedule->interest_written_off_derived - $nextSchedule->interest_repaid_derived;
                    $fees = $nextSchedule->fees - $nextSchedule->fees_waived_derived - $nextSchedule->fees_written_off_derived - $nextSchedule->fees_repaid_derived;
                    $penalties = $nextSchedule->penalties - $nextSchedule->penalties_waived_derived - $nextSchedule->penalties_written_off_derived - $nextSchedule->penalties_repaid_derived;
                    
                    $nextPayment = $principal + $interest + $fees + $penalties;
                    $nextDueDate = $nextSchedule->due_date;
                }
            }
            
            $totalExpected = $arrears + $nextPayment;
            
            return response()->json([
                'success' => true,
                'arrears' => $arrears,
                'arrears_formatted' => number_format($arrears, 2),
                'next_payment' => $nextPayment,
                'next_payment_formatted' => number_format($nextPayment, 2),
                'next_due_date' => $nextDueDate ? \Carbon\Carbon::parse($nextDueDate)->format('Y-m-d') : null,
                'total_expected' => $totalExpected,
                'total_expected_formatted' => number_format($totalExpected, 2),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading loan payment info: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get client accounts (savings or loans) via AJAX
     */
    public function get_client_accounts(Request $request)
    {
        $clientId = $request->client_id;
        $type = $request->type;

        try {
            if ($type === 'savings_deposit') {
                $accounts = Savings::where('client_id', $clientId)
                    ->where('status', 'active')
                    ->with('savings_product')
                    ->get()
                    ->map(function ($savings) {
                        return [
                            'id' => $savings->id,
                            'name' => ($savings->savings_product ? $savings->savings_product->name : 'Savings') . ' - ' . $savings->account_number,
                            'balance' => number_format($savings->balance_derived ?? 0, 2),
                        ];
                    });
            } elseif ($type === 'loan_repayment') {
                // Get individual loans
                $individualLoans = Loan::where('client_id', $clientId)
                    ->where('client_type', 'client')
                    ->where('status', 'active')
                    ->with('loan_product')
                    ->get();
                
                // Get group loans where client is a member with their allocation
                $groupLoans = Loan::where('status', 'active')
                    ->where('client_type', 'group')
                    ->whereHas('group.members', function($query) use ($clientId) {
                        $query->where('client_id', $clientId);
                    })
                    ->with(['loan_product', 'group', 'memberAllocations' => function($query) use ($clientId) {
                        $query->where('client_id', $clientId);
                    }])
                    ->get();
                
                // Combine both
                $allLoans = $individualLoans->merge($groupLoans);
                
                $accounts = $allLoans->map(function ($loan) {
                    $loanType = $loan->client_type === 'group' ? ' (Group)' : '';
                    $groupName = $loan->client_type === 'group' && $loan->group ? ' - ' . $loan->group->name : '';
                    
                    // For group loans, use member's allocation; for individual loans, use loan balance
                    if ($loan->client_type === 'group' && $loan->memberAllocations && $loan->memberAllocations->isNotEmpty()) {
                        $allocation = $loan->memberAllocations->first();
                        
                        // Principal outstanding = outstanding_balance (which is updated by observer)
                        $principal = $allocation->outstanding_balance ?? 0;
                        
                        // Interest outstanding - use the interest_outstanding field
                        $interest = $allocation->interest_outstanding ?? 0;
                        
                        // Total outstanding
                        $total = $principal + $interest;
                    } else {
                        // Individual loan - use full outstanding amounts
                        $principal = $loan->principal_outstanding_derived ?? 0;
                        $interest = $loan->interest_outstanding_derived ?? 0;
                        $total = $principal + $interest;
                    }
                    
                    // Format balance display
                    $balanceDisplay = number_format($principal, 2) . ' + ' . number_format($interest, 2) . ' = ' . number_format($total, 2);
                    
                    return [
                        'id' => $loan->id,
                        'name' => ($loan->loan_product ? $loan->loan_product->name : 'Loan') . ' - #' . $loan->id . $groupName . $loanType,
                        'balance' => $balanceDisplay,
                    ];
                });
            } else {
                $accounts = [];
            }

            return response()->json($accounts);
        } catch (\Exception $e) {
            \Log::error('Error loading client accounts: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created collection
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_agent_id' => 'required|exists:field_agents,id',
            'collection_type' => 'required|in:savings_deposit,loan_repayment,share_purchase',
            'reference_id' => 'required|integer',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'collection_date' => 'required|date',
            'collection_time' => 'required',
            'payment_method' => 'required|in:cash,mobile_money,cheque,bank_transfer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string',
            'photo_proof' => 'nullable|image|max:5120',
            'notes' => 'nullable|string',
        ]);

        $data = $request->except('photo_proof');
        $data['status'] = 'pending';

        // Handle photo upload
        if ($request->hasFile('photo_proof')) {
            $file = $request->file('photo_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/field_collections'), $filename);
            $data['photo_proof'] = 'uploads/field_collections/' . $filename;
        }

        $collection = FieldCollection::create($data);

        return redirect('field-agent/collection')->with('success', 'Collection recorded successfully. Receipt: ' . $collection->receipt_number);
    }

    /**
     * Display the specified collection
     */
    public function show($id)
    {
        $collection = FieldCollection::with(['fieldAgent.user', 'client', 'verifiedBy', 'postedBy'])->findOrFail($id);

        // Get reference details
        if ($collection->collection_type === 'savings_deposit') {
            $reference = Savings::find($collection->reference_id);
        } elseif ($collection->collection_type === 'loan_repayment') {
            $reference = Loan::find($collection->reference_id);
        } else {
            $reference = null;
        }

        return theme_view('fieldagent::collection.show', compact('collection', 'reference'));
    }

    /**
     * Display verification dashboard
     */
    public function verify_index()
    {
        $pendingCount = FieldCollection::pending()->count();
        return theme_view('fieldagent::collection.verify', compact('pendingCount'));
    }

    /**
     * Verify a collection
     */
    public function verify(Request $request, $id)
    {
        $collection = FieldCollection::findOrFail($id);

        // If GET request, redirect to show page
        if ($request->isMethod('get')) {
            return redirect(url('field-agent/collection/' . $id . '/show'));
        }

        // POST request - process verification
        if (!$collection->canBeVerified()) {
            flash('This collection cannot be verified')->error();
            return redirect()->back();
        }

        $collection->verify(Auth::id());

        flash('Collection verified successfully')->success();
        return redirect()->back();
    }

    /**
     * Reject a collection
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $collection = FieldCollection::findOrFail($id);

        if (!$collection->canBeVerified()) {
            return redirect()->back()->with('error', 'This collection cannot be rejected');
        }

        $collection->reject(Auth::id(), $request->rejection_reason);

        return redirect()->back()->with('success', 'Collection rejected');
    }

    /**
     * Post a collection to accounting
     */
    public function post($id)
    {
        $collection = FieldCollection::findOrFail($id);

        if (!$collection->canBePosted()) {
            flash('This collection cannot be posted')->error();
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            // Post the collection
            $collection->post(Auth::id());

            // Create actual transaction based on collection type
            if ($collection->collection_type === 'loan_repayment' && $collection->reference_id) {
                // Record loan repayment
                $loan = \Modules\Loan\Entities\Loan::findOrFail($collection->reference_id);
                
                // Create payment detail
                $payment_detail = new \Modules\Core\Entities\PaymentDetail();
                $payment_detail->created_by_id = Auth::id();
                $payment_detail->payment_type_id = $collection->payment_type_id ?? 1;
                $payment_detail->transaction_type = 'loan_transaction';
                $payment_detail->amount = $collection->amount;
                $payment_detail->receipt = $collection->receipt_number;
                $payment_detail->save();
                
                // Create loan transaction
                $loan_transaction = new \Modules\Loan\Entities\LoanTransaction();
                $loan_transaction->created_by_id = Auth::id();
                $loan_transaction->loan_id = $loan->id;
                $loan_transaction->payment_detail_id = $payment_detail->id;
                $loan_transaction->name = 'Repayment';
                $loan_transaction->loan_transaction_type_id = 1; // Repayment
                $loan_transaction->submitted_on = $collection->collection_date;
                $loan_transaction->created_on = date("Y-m-d");
                $loan_transaction->amount = $collection->amount;
                $loan_transaction->credit = $collection->amount;
                $loan_transaction->save();
                
                // Update loan repayment schedules
                $balance = $collection->amount;
                $schedules = $loan->repayment_schedules()
                    ->orderBy('due_date', 'asc')
                    ->get();
                
                foreach ($schedules as $schedule) {
                    if ($balance <= 0) break;
                    
                    // Calculate outstanding amounts
                    $principal_outstanding = $schedule->principal - $schedule->principal_repaid_derived;
                    $interest_outstanding = $schedule->interest - $schedule->interest_repaid_derived;
                    $fees_outstanding = $schedule->fees - $schedule->fees_repaid_derived;
                    $penalties_outstanding = $schedule->penalties - $schedule->penalties_repaid_derived;
                    
                    $total_outstanding = $principal_outstanding + $interest_outstanding + $fees_outstanding + $penalties_outstanding;
                    
                    if ($total_outstanding > 0) {
                        // Pay penalties first, then fees, then interest, then principal
                        if ($penalties_outstanding > 0 && $balance > 0) {
                            $penalty_payment = min($penalties_outstanding, $balance);
                            $schedule->penalties_repaid_derived += $penalty_payment;
                            $balance -= $penalty_payment;
                        }
                        
                        if ($fees_outstanding > 0 && $balance > 0) {
                            $fee_payment = min($fees_outstanding, $balance);
                            $schedule->fees_repaid_derived += $fee_payment;
                            $balance -= $fee_payment;
                        }
                        
                        if ($interest_outstanding > 0 && $balance > 0) {
                            $interest_payment = min($interest_outstanding, $balance);
                            $schedule->interest_repaid_derived += $interest_payment;
                            $balance -= $interest_payment;
                        }
                        
                        if ($principal_outstanding > 0 && $balance > 0) {
                            $principal_payment = min($principal_outstanding, $balance);
                            $schedule->principal_repaid_derived += $principal_payment;
                            $balance -= $principal_payment;
                        }
                        
                        $schedule->save();
                    }
                }
                
                // Update loan totals
                $loan->principal_repaid_derived = $loan->repayment_schedules()->sum('principal_repaid_derived');
                $loan->interest_repaid_derived = $loan->repayment_schedules()->sum('interest_repaid_derived');
                $loan->fees_repaid_derived = $loan->repayment_schedules()->sum('fees_repaid_derived');
                $loan->penalties_repaid_derived = $loan->repayment_schedules()->sum('penalties_repaid_derived');
                
                // Check if loan is fully paid
                $total_due = $loan->principal + $loan->interest_disbursed_derived + $loan->fees_disbursed_derived + $loan->penalties_disbursed_derived;
                $total_paid = $loan->principal_repaid_derived + $loan->interest_repaid_derived + $loan->fees_repaid_derived + $loan->penalties_repaid_derived;
                
                if ($total_paid >= $total_due) {
                    $loan->status = 'closed';
                    $loan->closed_on_date = date("Y-m-d");
                }
                
                $loan->save();
                
                // Update group member allocation if it's a group loan
                if ($loan->client_type === 'group' && $collection->client_id) {
                    $allocation = \Modules\Client\Entities\GroupMemberLoanAllocation::where('loan_id', $loan->id)
                        ->where('client_id', $collection->client_id)
                        ->first();
                    
                    if ($allocation) {
                        // Update member's paid amounts
                        $member_balance = $collection->amount;
                        
                        // Calculate member's outstanding
                        $member_principal_outstanding = $allocation->allocated_amount - $allocation->principal_paid;
                        $member_interest_outstanding = $allocation->allocated_interest - $allocation->interest_paid;
                        
                        // Pay interest first, then principal
                        if ($member_interest_outstanding > 0 && $member_balance > 0) {
                            $interest_payment = min($member_interest_outstanding, $member_balance);
                            $allocation->interest_paid += $interest_payment;
                            $member_balance -= $interest_payment;
                        }
                        
                        if ($member_principal_outstanding > 0 && $member_balance > 0) {
                            $principal_payment = min($member_principal_outstanding, $member_balance);
                            $allocation->principal_paid += $principal_payment;
                            $member_balance -= $principal_payment;
                        }
                        
                        $allocation->save();
                    }
                }
                
            } elseif ($collection->collection_type === 'savings_deposit' && $collection->reference_id) {
                // Record savings deposit
                $savings = \Modules\Savings\Entities\Savings::findOrFail($collection->reference_id);
                
                $payment_detail = new \Modules\Core\Entities\PaymentDetail();
                $payment_detail->created_by_id = Auth::id();
                $payment_detail->payment_type_id = $collection->payment_type_id ?? 1;
                $payment_detail->transaction_type = 'savings_transaction';
                $payment_detail->amount = $collection->amount;
                $payment_detail->receipt = $collection->receipt_number;
                $payment_detail->save();
                
                $savings_transaction = new \Modules\Savings\Entities\SavingsTransaction();
                $savings_transaction->created_by_id = Auth::id();
                $savings_transaction->savings_id = $savings->id;
                $savings_transaction->payment_detail_id = $payment_detail->id;
                $savings_transaction->name = 'Deposit';
                $savings_transaction->savings_transaction_type_id = 1; // Deposit
                $savings_transaction->submitted_on = $collection->collection_date;
                $savings_transaction->created_on = date("Y-m-d");
                $savings_transaction->amount = $collection->amount;
                $savings_transaction->credit = $collection->amount;
                $savings_transaction->save();
                
                // Update savings balance
                $savings->balance_derived = $savings->transactions()->sum('credit') - $savings->transactions()->sum('debit');
                $savings->save();
            }

            DB::commit();
            flash('Collection posted to accounting successfully')->success();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error posting collection: ' . $e->getMessage());
            flash('Error posting collection: ' . $e->getMessage())->error();
            return redirect()->back();
        }
    }
}
