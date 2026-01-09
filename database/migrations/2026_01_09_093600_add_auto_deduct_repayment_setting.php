<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Setting\Entities\Setting;

class AddAutoDeductRepaymentSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add auto-deduct repayment setting
        $setting = Setting::where('setting_key', 'auto_deduct_loan_repayment_from_savings')->first();
        
        if (!$setting) {
            $setting = new Setting();
            $setting->name = 'Auto-Deduct Loan Repayment from Savings';
            $setting->setting_key = 'auto_deduct_loan_repayment_from_savings';
            $setting->module = 'Loan';
            $setting->setting_value = '0'; // Disabled by default
            $setting->category = 'system'; // Must be: email, sms, general, system, update, other
            $setting->type = 'checkbox'; // Valid types from schema
            $setting->options = null;
            $setting->class = null;
            $setting->required = 0;
            $setting->db_columns = null;
            $setting->info = 'Automatically deduct loan repayments from client savings accounts when installments are due';
            $setting->displayed = 1;
            $setting->rules = null;
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('setting_key', 'auto_deduct_loan_repayment_from_savings')->delete();
    }
}
