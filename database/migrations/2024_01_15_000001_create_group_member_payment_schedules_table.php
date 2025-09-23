<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMemberPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_member_payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allocation_id');
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('client_id');
            $table->integer('installment_number');
            $table->date('due_date');
            $table->decimal('principal_due', 65, 6)->default(0);
            $table->decimal('interest_due', 65, 6)->default(0);
            $table->decimal('fees_due', 65, 6)->default(0);
            $table->decimal('penalties_due', 65, 6)->default(0);
            $table->decimal('total_due', 65, 6)->default(0);
            $table->decimal('principal_paid', 65, 6)->default(0);
            $table->decimal('interest_paid', 65, 6)->default(0);
            $table->decimal('fees_paid', 65, 6)->default(0);
            $table->decimal('penalties_paid', 65, 6)->default(0);
            $table->decimal('total_paid', 65, 6)->default(0);
            $table->decimal('outstanding_balance', 65, 6)->default(0);
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue', 'defaulted'])->default('pending');
            $table->date('paid_date')->nullable();
            $table->decimal('excess_payment', 65, 6)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('allocation_id')->references('id')->on('group_member_loan_allocations')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            
            $table->index(['allocation_id', 'installment_number'], 'gmps_allocation_installment_idx');
            $table->index(['loan_id', 'client_id'], 'gmps_loan_client_idx');
            $table->index(['due_date', 'status'], 'gmps_due_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_member_payment_schedules');
    }
}
