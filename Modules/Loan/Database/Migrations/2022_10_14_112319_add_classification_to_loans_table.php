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
        DB::statement("alter TABLE loans modify column interest_rate_type enum('day','week','month','year','principal') not null");
        Schema::table('loans', function (Blueprint $table) {
            $table->string('classification')->nullable();
            $table->tinyInteger('deduct_interest_from_principal')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['classification', 'deduct_interest_from_principal']);
        });
    }
};
