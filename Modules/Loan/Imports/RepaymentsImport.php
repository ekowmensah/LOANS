<?php

namespace Modules\Loan\Imports;

use App\Events\RepaymentCreated;
use App\Helpers\GeneralHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\Core\Entities\PaymentDetail;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Loan\Events\TransactionUpdated;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RepaymentsImport implements ToCollection, WithHeadingRow, WithChunkReading, WithEvents, ShouldQueue
{
    use RemembersRowNumber, RegistersEventListeners;

    public $user;
    public $results = [];
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
    protected $worksheet;

    public function __construct($user = null)
    {
        $this->user = $user;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function columnFormats(): array
    {
        return [
            'date' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $loanID = $row['loan_id'];
            $amount = $row['amount'];
            $date = $row['date'];
            $paymentMethodID = $row['payment_method'];
            $receipt = $row['receipt'];
            $loan = Loan::with('loan_product')->find($loanID);
            //payment details
            $payment_detail = new PaymentDetail();
            $payment_detail->created_by_id = Auth::id();
            $payment_detail->payment_type_id = $paymentMethodID;
            $payment_detail->transaction_type = 'loan_transaction';
            $payment_detail->receipt = $receipt;
            $payment_detail->save();
            $loan_transaction = new LoanTransaction();
            $loan_transaction->created_by_id = $this->user->id;
            $loan_transaction->loan_id = $loan->id;
            $loan_transaction->payment_detail_id = $payment_detail->id;
            $loan_transaction->name = trans_choice('loan::general.repayment', 1);
            $loan_transaction->loan_transaction_type_id = 2;
            $loan_transaction->submitted_on = $date;
            $loan_transaction->created_on = date("Y-m-d");
            $loan_transaction->amount = $amount;
            $loan_transaction->credit = $amount;
            $loan_transaction->save();
            //fire transaction updated event
            event(new TransactionUpdated($loan));
        }
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->worksheet = $event->getSheet();

            },
        ];
    }

}
