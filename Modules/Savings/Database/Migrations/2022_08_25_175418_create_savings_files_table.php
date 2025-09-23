<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by_id')->unsigned()->nullable();
            $table->bigInteger('savings_file_type_id')->unsigned()->nullable();
            $table->bigInteger('savings_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('size')->nullable();
            $table->text('link')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings_files');
    }
};
