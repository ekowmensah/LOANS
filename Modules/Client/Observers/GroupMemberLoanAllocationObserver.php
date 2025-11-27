<?php

namespace Modules\Client\Observers;

use Modules\Client\Entities\GroupMemberLoanAllocation;

class GroupMemberLoanAllocationObserver
{
    /**
     * Handle the GroupMemberLoanAllocation "saving" event.
     * Update interest_outstanding whenever interest_paid changes
     *
     * @param  \Modules\Client\Entities\GroupMemberLoanAllocation  $allocation
     * @return void
     */
    public function saving(GroupMemberLoanAllocation $allocation)
    {
        // If interest_paid has changed, update interest_outstanding
        if ($allocation->isDirty('interest_paid')) {
            $allocation->interest_outstanding = ($allocation->allocated_interest ?? 0) - ($allocation->interest_paid ?? 0);
        }
        
        // If principal_paid has changed, update outstanding_balance
        if ($allocation->isDirty('principal_paid')) {
            $allocation->outstanding_balance = ($allocation->allocated_amount ?? 0) - ($allocation->principal_paid ?? 0);
        }
        
        // Update total_paid if any payment field changed
        if ($allocation->isDirty(['principal_paid', 'interest_paid', 'fees_paid', 'penalties_paid'])) {
            $allocation->total_paid = ($allocation->principal_paid ?? 0) 
                                    + ($allocation->interest_paid ?? 0) 
                                    + ($allocation->fees_paid ?? 0) 
                                    + ($allocation->penalties_paid ?? 0);
        }
    }
    
    /**
     * Handle the GroupMemberLoanAllocation "created" event.
     * Calculate interest if not provided
     *
     * @param  \Modules\Client\Entities\GroupMemberLoanAllocation  $allocation
     * @return void
     */
    public function created(GroupMemberLoanAllocation $allocation)
    {
        // If allocated_interest is not set, calculate it
        if (!$allocation->allocated_interest || $allocation->allocated_interest == 0) {
            $allocation->calculateAllocatedInterest();
            $allocation->saveQuietly(); // Save without triggering events again
        }
    }
}
