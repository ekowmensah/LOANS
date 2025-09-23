<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("alter TABLE loan_products modify column interest_rate_type enum('day','week','month','year','principal') not null ");
        Schema::table('loan_products', function (Blueprint $table) {
            $table->tinyInteger('deduct_interest_from_principal')->default(0);
            $table->tinyInteger('disallow_interest_rate_adjustment')->default(0);
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
            $table->dropColumn(['deduct_interest_from_principal', 'disallow_interest_rate_adjustment']);
        });
    }
};
