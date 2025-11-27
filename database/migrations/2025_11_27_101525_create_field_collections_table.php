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
        Schema::create('field_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_agent_id');
            $table->enum('collection_type', ['savings_deposit', 'loan_repayment', 'share_purchase'])->default('savings_deposit');
            $table->unsignedBigInteger('reference_id')->comment('savings_id or loan_id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('amount', 65, 2);
            $table->date('collection_date');
            $table->time('collection_time');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_address')->nullable();
            $table->string('receipt_number')->unique();
            $table->enum('payment_method', ['cash', 'mobile_money', 'cheque', 'bank_transfer'])->default('cash');
            $table->enum('status', ['pending', 'verified', 'rejected', 'posted'])->default('pending');
            $table->unsignedBigInteger('verified_by_user_id')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('posted_by_user_id')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('photo_proof')->nullable()->comment('Receipt or payment proof photo');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('field_agent_id')->references('id')->on('field_agents')->onDelete('restrict');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('verified_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('posted_by_user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['field_agent_id', 'collection_date']);
            $table->index('status');
            $table->index('collection_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_collections');
    }
};
