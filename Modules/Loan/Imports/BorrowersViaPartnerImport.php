<?php

namespace App\Imports;

use App\Helpers\GeneralHelper;
use App\Models\Borrower;
use App\Models\BorrowerGroup;
use App\Models\BorrowerGroupMember;
use App\Models\Partner;
use App\Notifications\BorrowersImportFinishedNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BorrowersViaPartnerImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public $partnerID;
    public $user;
    public $results = [];
    public $email='bumiloans@gmail.com'; // hardcode the importers email
    use RemembersRowNumber;
    public function __construct($partnerID,$user=null)
    {
        $this->partnerID = $partnerID;
        $this->user = $user;
    }

    public function chunkSize(): int
    {
        return 100;
    }
    public function collection(Collection $rows)
    {
        $partner = Partner::find($this->partnerID);
        foreach ($rows as $row) {
            if ($row['first_name'] == "" && $row['last_name'] == null && $row['gender']
                && $row['birth_date'] == null && $row['mobile'] == null && $row['email'] == null
                && $row['nrc_passport'] == null && $row['amount_limit'] == null && $row['employee_number'] == null) {
                $this->results[] = 'An empty row has been skipped';
                \flash('An empty row has been skipped')->warning();
            }

            //dd($row['nrc_passport']);

            $str = $row['nrc_passport'];
            //dd(strlen($str));

            if ((strlen($str)) === 11) {
                //dd("true");
            } else {
                $this->results[] = 'Invalid NRC length on nrc No (' . $row['nrc_passport'] . ') for borrower ' . $row['first_name'];
                \flash('Invalid NRC length on nrc No (' . $row['nrc_passport'] . ') for borrower ' . $row['first_name'])->warning();
                continue;
            }

            $mystring = $row['nrc_passport'];
            $findme = '/';
            $pos = strpos($mystring, $findme);
            //dd($pos);
            if ($pos !== 6) {
                $this->results[] = 'Invalid NRC format (' . $row['nrc_passport'] . ') for borrower ' . $row['first_name'];
                \flash('Invalid NRC format (' . $row['nrc_passport'] . ') for borrower ' . $row['first_name'])->warning();
                continue;
            }

            $pos = strrpos($mystring, "/");
            if ($pos !== 9) {
                $this->results[] = 'Invalid NRC format (' . $row['nrc_passport'] . ') LAST SLASHE for borrower ' . $row['first_name'];
                \flash('Invalid NRC format (' . $row['nrc_passport'] . ') LAST SLASHE for borrower ' . $row['first_name'])->warning();
                continue;
            }


            // dd($row['nrc_passport']);
            $num = $row['mobile'];
            $find1 = "+260";
            $find2 = "260";
            $find3 = "0";
            $num1 = "";
            //$pos = strpos($mystring, $findme);
            //dd(strpos($num, $find1) === 0);
            if (strpos($num, $find1) === 0) {
                $num1 = str_replace($find1, "260", $num);

            } else if (strpos($num, $find2) === 0) {
                $num1 = str_replace($find2, "260", $num);

            } else if (strpos($num, $find3) === 0) {
                $num1 = str_replace($find3, "260", $num);
            } else {
                $pre = "260";
                $num1 = $pre . '' . $num;
            }

            $data = Borrower::where('unique_number', $row['nrc_passport'])
                ->orWhere('mobile', $num1)->first();

           // dd($data);

            if (!$data) {

                $borrower = new Borrower;

                $borrower->first_name = $row['first_name'];
                $borrower->last_name = $row['last_name'];

                $borrower->unique_number = $row['nrc_passport'];


                if (strtolower($row['gender']) == 'male' || strtolower($row['gender']) == 'm') {
                    $borrower->gender = 'Male';
                } else {
                    $borrower->gender = 'Female';
                }

                $borrower->mobile = $num1;
                $borrower->amount_limit = $row['amount_limit'];
                $borrower->employee_number = $row['employee_number'];
                $borrower->email = $row['email'];
                $newDateFormat = Carbon::createFromFormat('d/m/Y',$row['birth_date']);
                $borrower->dob = $newDateFormat;
                $borrower->username = $row['mobile'];
                $borrower->password = md5('1234');
                $borrower->active = '1';
                $borrower->country_id = '245';
                $borrower->user_id = '1';
                $borrower->branch_id = '1';
                $borrower->loan_officer_id = $partner->loan_officer_id;
                $borrower->loan_product_id = $partner->loan_product_id;
                $borrower->can_borrow = 1;
                $borrower->partner_id = $partner->id;
                if (strtolower($row['gender']) === 'male' || strtolower($row['gender']) === 'm') {
                    $borrower->title = 'Mr';
                } else {
                    $borrower->title = 'Ms';
                }
                $files = array();
                $borrower->files = serialize($files);
                $borrower->save();

                $short_code = "*788%23";
                $pin = "1234";

                $name = $borrower->first_name . ' ' . $borrower->last_name;
                $body = "Dear" . " " . $name . " Congratulations!!! Your Zaako Loans account has been opened, Dial " . $short_code . " and enter default Pin " . $pin . " to access your account. Thank you for using Zaako Loans.";
                GeneralHelper::send_sms($borrower->mobile, $body);

            } else {
                $this->results[] = 'Skipped existing borrower ' . $row['first_name'];
                \flash('Skipped existing borrower ' . $row['first_name'])->warning();

            }
        }
            Notification::route('mail', $this->email)->notify
            (new BorrowersImportFinishedNotification($this->results,$this->user));


    }
}
