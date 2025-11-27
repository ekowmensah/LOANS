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
        Schema::table('group_member_loan_allocations', function (Blueprint $table) {
            // Add allocated_interest field after allocated_amount
            $table->decimal('allocated_interest', 65, 6)->default(0)->after('allocated_amount');
            
            // Add interest_outstanding field to track remaining interest
            $table->decimal('interest_outstanding', 65, 6)->default(0)->after('allocated_interest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_member_loan_allocations', function (Blueprint $table) {
            $table->dropColumn(['allocated_interest', 'interest_outstanding']);
        });
    }
};
