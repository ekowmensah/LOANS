<?php

namespace Modules\Loan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoanProvisioningTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('loan_provisioning')->truncate();
        DB::table('loan_provisioning')->insert([
            [
                'name' => 'Current',
                'lower_limit' => 0,
                'upper_limit' => 89,
                'percentage' => 0,
            ],
            [
                'name' => 'Substandard',
                'lower_limit' => 90,
                'upper_limit' => 179,
                'percentage' =>20,
            ],
            [
                'name' => 'Doubtful',
                'lower_limit' => 180,
                'upper_limit' => 359,
                'percentage' => 50,
            ],
            [
                'name' => 'Loss',
                'lower_limit' => 360,
                'upper_limit' => null,
                'percentage' => 100,
            ],

        ]);
    }
}
