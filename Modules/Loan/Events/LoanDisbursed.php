<?php

namespace Modules\Loan\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Loan\Entities\Loan;

class LoanDisbursed
{
    use SerializesModels;

    public $loan;
    public $disbursementAmount;
    public $disbursementDate;
    public $paymentTypeId;

    /**
     * Create a new event instance.
     *
     * @param Loan $loan
     * @param float $disbursementAmount
     * @param string $disbursementDate
     * @param int $paymentTypeId
     */
    public function __construct(Loan $loan, $disbursementAmount, $disbursementDate, $paymentTypeId)
    {
        $this->loan = $loan;
        $this->disbursementAmount = $disbursementAmount;
        $this->disbursementDate = $disbursementDate;
        $this->paymentTypeId = $paymentTypeId;
    }
}
