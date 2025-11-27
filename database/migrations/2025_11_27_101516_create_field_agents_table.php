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
        Schema::create('field_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('agent_code')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->decimal('commission_rate', 5, 2)->default(0)->comment('Commission percentage');
            $table->decimal('target_amount', 65, 2)->default(0)->comment('Monthly target amount');
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->string('phone_number')->nullable();
            $table->string('national_id')->nullable();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('restrict');
            
            $table->index('agent_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_agents');
    }
};
