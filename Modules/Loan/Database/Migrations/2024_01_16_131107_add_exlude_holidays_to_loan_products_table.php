<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_products', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_products', 'exclude_weekends')) {
                $table->tinyInteger('exclude_weekends')->default(0);
            }
            if (!Schema::hasColumn('loan_products', 'exclude_holidays')) {
                $table->tinyInteger('exclude_holidays')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn([
                'exclude_weekends',
                'exclude_holidays'
            ]);
        });
    }
};
