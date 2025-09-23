<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToClientFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_files', function (Blueprint $table) {
            $table->unsignedBigInteger('client_file_type_id')->nullable();
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
        Schema::table('client_files', function (Blueprint $table) {
            $table->dropColumn(['status','client_file_type_id']);
        });
    }
}
