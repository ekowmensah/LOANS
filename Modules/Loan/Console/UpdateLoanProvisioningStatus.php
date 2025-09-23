<?php

namespace Modules\Loan\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanProvisioning;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateLoanProvisioningStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'loans:update-provisioning-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Loan Provisioning';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $loans = Loan::where('status', 'active')
            ->where('expected_maturity_date', '<=', Carbon::today()->format('Y-m-d'))
            ->where('total_outstanding_derived', '>', 0)
            ->get();
        foreach ($loans as $loan) {
            $days = Carbon::today()->diffInDays(Carbon::parse($loan->expected_maturity_date));
            //find the right provision
            $provision = LoanProvisioning::where('lower_limit', '>=', $days)->orderBy('lower_limit')->first();
            if (!empty($provision)) {
                $loan->loan_provisioning_id = $provision->id;
                $loan->save();
            }
        }
        return 0;
    }


}
