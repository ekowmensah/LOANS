<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeRepaymentFrequencyNullableInLoanProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL for ENUM columns as Doctrine DBAL doesn't support them
        DB::statement("ALTER TABLE loan_products MODIFY repayment_frequency INT NULL");
        DB::statement("ALTER TABLE loan_products MODIFY repayment_frequency_type ENUM('days', 'weeks', 'months', 'years') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE loan_products MODIFY repayment_frequency INT NOT NULL");
        DB::statement("ALTER TABLE loan_products MODIFY repayment_frequency_type ENUM('days', 'weeks', 'months', 'years') NOT NULL");
    }
}
