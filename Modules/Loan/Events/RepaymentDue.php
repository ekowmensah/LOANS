<?php

namespace Modules\Loan\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Loan\Entities\Loan;

class RepaymentDue
{
    use SerializesModels;

    public $loan;
    public $dueAmount;
    public $dueDate;

    /**
     * Create a new event instance.
     *
     * @param Loan $loan
     * @param float $dueAmount
     * @param string $dueDate
     * @return void
     */
    public function __construct(Loan $loan, $dueAmount, $dueDate)
    {
        $this->loan = $loan;
        $this->dueAmount = $dueAmount;
        $this->dueDate = $dueDate;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
