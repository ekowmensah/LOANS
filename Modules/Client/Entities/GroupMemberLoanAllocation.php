<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Loan\Entities\Loan;

class GroupMemberLoanAllocation extends Model
{
    protected $fillable = [
        'loan_id',
        'group_id', 
        'client_id',
        'allocated_amount',
        'allocated_interest',
        'interest_outstanding',
        'allocated_percentage',
        'principal_paid',
        'interest_paid',
        'fees_paid',
        'penalties_paid',
        'total_paid',
        'outstanding_balance',
        'status',
        'notes',
        'created_by_id'
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:6',
        'allocated_interest' => 'decimal:6',
        'interest_outstanding' => 'decimal:6',
        'allocated_percentage' => 'decimal:2',
        'principal_paid' => 'decimal:6',
        'interest_paid' => 'decimal:6',
        'fees_paid' => 'decimal:6',
        'penalties_paid' => 'decimal:6',
        'total_paid' => 'decimal:6',
        'outstanding_balance' => 'decimal:6',
    ];

    /**
     * Get the loan that this allocation belongs to
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the group that this allocation belongs to
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the client that this allocation belongs to
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the payment schedules for this allocation
     */
    public function paymentSchedules()
    {
        return $this->hasMany(GroupMemberPaymentSchedule::class, 'allocation_id');
    }

    /**
     * Generate payment schedules based on loan repayment schedule
     */
    public function generatePaymentSchedules()
    {
        // Get loan repayment schedules
        $loanSchedules = $this->loan->repayment_schedules()->orderBy('due_date')->get();
        
        foreach ($loanSchedules as $index => $loanSchedule) {
            // Calculate member's portion based on allocation percentage
            $memberPrincipal = ($loanSchedule->principal * $this->allocated_percentage) / 100;
            $memberInterest = ($loanSchedule->interest * $this->allocated_percentage) / 100;
            $memberFees = ($loanSchedule->fees * $this->allocated_percentage) / 100;
            $memberPenalties = ($loanSchedule->penalties * $this->allocated_percentage) / 100;
            
            GroupMemberPaymentSchedule::create([
                'allocation_id' => $this->id,
                'loan_id' => $this->loan_id,
                'client_id' => $this->client_id,
                'installment_number' => $index + 1,
                'due_date' => $loanSchedule->due_date,
                'principal_due' => $memberPrincipal,
                'interest_due' => $memberInterest,
                'fees_due' => $memberFees,
                'penalties_due' => $memberPenalties,
                'total_due' => $memberPrincipal + $memberInterest + $memberFees + $memberPenalties,
                'outstanding_balance' => $memberPrincipal + $memberInterest + $memberFees + $memberPenalties,
            ]);
        }
    }

    /**
     * Get next unpaid installment
     */
    public function getNextUnpaidInstallment()
    {
        return $this->paymentSchedules()
                    ->where('status', '!=', 'paid')
                    ->orderBy('installment_number')
                    ->first();
    }

    /**
     * Calculate and set allocated interest based on loan
     */
    public function calculateAllocatedInterest()
    {
        if (!$this->loan) {
            return 0;
        }

        // Get total loan interest
        $totalInterest = $this->loan->interest_derived ?? 0;
        
        // Calculate member's share based on percentage
        $memberInterest = ($totalInterest * $this->allocated_percentage) / 100;
        
        $this->allocated_interest = $memberInterest;
        $this->interest_outstanding = $memberInterest - $this->interest_paid;
        
        return $memberInterest;
    }

    /**
     * Calculate outstanding balance (principal only)
     */
    public function calculateOutstandingBalance()
    {
        $this->outstanding_balance = $this->allocated_amount - $this->principal_paid;
        return $this->outstanding_balance;
    }
    
    /**
     * Update interest outstanding after payment
     */
    public function updateInterestOutstanding()
    {
        $this->interest_outstanding = $this->allocated_interest - $this->interest_paid;
        return $this->interest_outstanding;
    }

    /**
     * Update total paid amount
     */
    public function updateTotalPaid()
    {
        $this->total_paid = $this->principal_paid + $this->interest_paid + $this->fees_paid + $this->penalties_paid;
        $this->calculateOutstandingBalance();
        return $this->total_paid;
    }

    /**
     * Get payment completion percentage
     */
    public function getPaymentPercentageAttribute()
    {
        if ($this->allocated_amount == 0) {
            return 0;
        }
        return round(($this->total_paid / $this->allocated_amount) * 100, 2);
    }

    /**
     * Check if allocation is fully paid
     */
    public function isFullyPaid()
    {
        return $this->outstanding_balance <= 0;
    }

    /**
     * Scope for active allocations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed allocations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
