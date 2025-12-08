<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateSavingsReferenceFormatOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the savings.reference_format setting to include the new format option
        DB::table('settings')
            ->where('setting_key', 'savings.reference_format')
            ->update([
                'options' => 'YEAR/Sequence Number (SL/2014/001),YEAR/MONTH/Sequence Number (SL/2014/08/001),Sequence Number,Random Number,Branch Product Sequence Number,Prefix 6 Random Digits Year (AFA123456YEAR)'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to the old options
        DB::table('settings')
            ->where('setting_key', 'savings.reference_format')
            ->update([
                'options' => 'YEAR/Sequence Number (SL/2014/001),YEAR/MONTH/Sequence Number (SL/2014/08/001),Sequence Number,Random Number,Branch Product Sequence Number'
            ]);
    }
}
