<?php

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
        Schema::create('loan_provisioning', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('lower_limit')->nullable();
            $table->integer('upper_limit')->nullable();
            $table->double('percentage',65,2)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('loan_provisioning');
    }
};
