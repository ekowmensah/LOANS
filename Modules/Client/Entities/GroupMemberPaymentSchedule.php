<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Loan\Entities\Loan;

class GroupMemberPaymentSchedule extends Model
{
    protected $fillable = [
        'allocation_id',
        'loan_id',
        'client_id',
        'installment_number',
        'due_date',
        'principal_due',
        'interest_due',
        'fees_due',
        'penalties_due',
        'total_due',
        'principal_paid',
        'interest_paid',
        'fees_paid',
        'penalties_paid',
        'total_paid',
        'outstanding_balance',
        'status',
        'paid_date',
        'excess_payment',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'principal_due' => 'decimal:6',
        'interest_due' => 'decimal:6',
        'fees_due' => 'decimal:6',
        'penalties_due' => 'decimal:6',
        'total_due' => 'decimal:6',
        'principal_paid' => 'decimal:6',
        'interest_paid' => 'decimal:6',
        'fees_paid' => 'decimal:6',
        'penalties_paid' => 'decimal:6',
        'total_paid' => 'decimal:6',
        'outstanding_balance' => 'decimal:6',
        'excess_payment' => 'decimal:6',
    ];

    /**
     * Get the allocation that this schedule belongs to
     */
    public function allocation()
    {
        return $this->belongsTo(GroupMemberLoanAllocation::class, 'allocation_id');
    }

    /**
     * Get the loan that this schedule belongs to
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the client that this schedule belongs to
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Calculate outstanding balance for this installment
     */
    public function calculateOutstandingBalance()
    {
        $this->outstanding_balance = $this->total_due - $this->total_paid;
        return $this->outstanding_balance;
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
     * Check if installment is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }

    /**
     * Check if installment is fully paid
     */
    public function isFullyPaid()
    {
        return $this->outstanding_balance <= 0;
    }

    /**
     * Get the next unpaid installment for this allocation
     */
    public static function getNextUnpaidInstallment($allocationId)
    {
        return self::where('allocation_id', $allocationId)
                   ->where('status', '!=', 'paid')
                   ->orderBy('installment_number')
                   ->first();
    }

    /**
     * Process payment for this installment
     */
    public function processPayment($paymentAmount, $paymentDate = null)
    {
        $paymentDate = $paymentDate ?: now();
        $remainingPayment = $paymentAmount;
        
        // Pay principal first
        if ($remainingPayment > 0 && $this->principal_due > $this->principal_paid) {
            $principalToPay = min($remainingPayment, $this->principal_due - $this->principal_paid);
            $this->principal_paid += $principalToPay;
            $remainingPayment -= $principalToPay;
        }
        
        // Then interest
        if ($remainingPayment > 0 && $this->interest_due > $this->interest_paid) {
            $interestToPay = min($remainingPayment, $this->interest_due - $this->interest_paid);
            $this->interest_paid += $interestToPay;
            $remainingPayment -= $interestToPay;
        }
        
        // Then fees
        if ($remainingPayment > 0 && $this->fees_due > $this->fees_paid) {
            $feesToPay = min($remainingPayment, $this->fees_due - $this->fees_paid);
            $this->fees_paid += $feesToPay;
            $remainingPayment -= $feesToPay;
        }
        
        // Finally penalties
        if ($remainingPayment > 0 && $this->penalties_due > $this->penalties_paid) {
            $penaltiesToPay = min($remainingPayment, $this->penalties_due - $this->penalties_paid);
            $this->penalties_paid += $penaltiesToPay;
            $remainingPayment -= $penaltiesToPay;
        }
        
        // Store excess payment
        if ($remainingPayment > 0) {
            $this->excess_payment = $remainingPayment;
        }
        
        $this->updateTotalPaid();
        $this->updateStatus();
        $this->paid_date = $paymentDate;
        
        return $remainingPayment; // Return excess payment
    }

    /**
     * Update payment status based on amounts paid
     */
    public function updateStatus()
    {
        if ($this->total_paid >= $this->total_due) {
            $this->status = 'paid';
        } elseif ($this->total_paid > 0) {
            $this->status = 'partial';
        } elseif ($this->isOverdue()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
    }

    /**
     * Mark as defaulted
     */
    public function markAsDefaulted($reason = null)
    {
        $this->status = 'defaulted';
        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . ' | ' : '') . 'Defaulted: ' . $reason;
        }
        $this->save();
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeDefaulted($query)
    {
        return $query->where('status', 'defaulted');
    }
}
