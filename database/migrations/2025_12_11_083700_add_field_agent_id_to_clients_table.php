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
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('field_agent_id')->nullable()->after('loan_officer_id');
            
            $table->foreign('field_agent_id')
                  ->references('id')
                  ->on('field_agents')
                  ->onDelete('set null');
            
            $table->index('field_agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['field_agent_id']);
            $table->dropColumn('field_agent_id');
        });
    }
};
