<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoanTermTypeToLoanProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_products', 'loan_term_type')) {
                $table->enum('loan_term_type', ['days', 'weeks', 'months', 'years'])
                      ->default('months')
                      ->after('maximum_loan_term');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            if (Schema::hasColumn('loan_products', 'loan_term_type')) {
                $table->dropColumn('loan_term_type');
            }
        });
    }
}
