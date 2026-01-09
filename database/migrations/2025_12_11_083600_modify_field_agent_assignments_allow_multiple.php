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
        Schema::table('field_agent_client_assignments', function (Blueprint $table) {
            // Drop the unique constraint that limits one active assignment per client
            $table->dropUnique('unique_active_client_assignment');
            
            // Add a new composite unique constraint to prevent duplicate agent-client pairs
            $table->unique(['field_agent_id', 'client_id', 'status'], 'unique_agent_client_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_agent_client_assignments', function (Blueprint $table) {
            // Restore the old unique constraint
            $table->dropUnique('unique_agent_client_active');
            $table->unique(['client_id', 'status'], 'unique_active_client_assignment');
        });
    }
};
