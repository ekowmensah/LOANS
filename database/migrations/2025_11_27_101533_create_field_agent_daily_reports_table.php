<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('field_agent_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_agent_id');
            $table->date('report_date');
            $table->integer('total_collections')->default(0);
            $table->decimal('total_amount_collected', 65, 2)->default(0);
            $table->integer('total_clients_visited')->default(0);
            $table->integer('total_clients_paid')->default(0);
            $table->decimal('opening_cash_balance', 65, 2)->default(0);
            $table->decimal('closing_cash_balance', 65, 2)->default(0);
            $table->decimal('cash_deposited_to_branch', 65, 2)->default(0);
            $table->unsignedBigInteger('deposited_by_user_id')->nullable()->comment('Teller who received deposit');
            $table->string('deposit_receipt_number')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('field_agent_id')->references('id')->on('field_agents')->onDelete('cascade');
            $table->foreign('deposited_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unique(['field_agent_id', 'report_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_agent_daily_reports');
    }
};
