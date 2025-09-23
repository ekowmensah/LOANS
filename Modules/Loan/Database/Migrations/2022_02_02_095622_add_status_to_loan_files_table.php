<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLoanFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_files', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_file_type_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_files', function (Blueprint $table) {
            $table->dropColumn(['status', 'loan_file_type_id']);
        });
    }
}
