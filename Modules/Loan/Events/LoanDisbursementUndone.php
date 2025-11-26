<?php

namespace Modules\Loan\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Loan\Entities\Loan;

class LoanDisbursementUndone
{
    use SerializesModels;

    public $loan;

    /**
     * Create a new event instance.
     *
     * @param Loan $loan
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }
}
