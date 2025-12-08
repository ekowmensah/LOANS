<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->date('approved_on_date')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by_user_id')->nullable()->after('approved_on_date');
            $table->text('approved_notes')->nullable()->after('approved_by_user_id');
            $table->date('rejected_on_date')->nullable()->after('approved_notes');
            $table->unsignedBigInteger('rejected_by_user_id')->nullable()->after('rejected_on_date');
            $table->text('rejected_notes')->nullable()->after('rejected_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'approved_on_date',
                'approved_by_user_id',
                'approved_notes',
                'rejected_on_date',
                'rejected_by_user_id',
                'rejected_notes'
            ]);
        });
    }
}
