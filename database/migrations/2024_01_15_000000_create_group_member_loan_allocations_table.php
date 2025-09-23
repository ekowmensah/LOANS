<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMemberLoanAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_member_loan_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('allocated_amount', 65, 6)->default(0);
            $table->decimal('allocated_percentage', 5, 2)->default(0);
            $table->decimal('principal_paid', 65, 6)->default(0);
            $table->decimal('interest_paid', 65, 6)->default(0);
            $table->decimal('fees_paid', 65, 6)->default(0);
            $table->decimal('penalties_paid', 65, 6)->default(0);
            $table->decimal('total_paid', 65, 6)->default(0);
            $table->decimal('outstanding_balance', 65, 6)->default(0);
            $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
            
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['loan_id', 'group_id']);
            $table->index(['client_id']);
            $table->unique(['loan_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_member_loan_allocations');
    }
}
