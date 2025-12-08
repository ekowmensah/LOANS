<?php
/**
 * Update Teller Controller with all enhancements
 */

echo "Updating Teller Controller...\n";

$file = __DIR__ . '/Modules/Teller/Http/Controllers/TellerController.php';
$content = file_get_contents($file);

// 1. Update index method to include today's summary
$old_index = "    public function index()\n    {\n        \$payment_types = PaymentType::where('active', 1)->get();\n        return theme_view('teller::teller.index', compact('payment_types'));\n    }";

$new_index = "    public function index()\n    {\n        \$payment_types = PaymentType::where('active', 1)->get();\n        \n        // Get today's transactions summary for current teller\n        \$today = date('Y-m-d');\n        \$user_id = Auth::id();\n        \n        \$today_deposits = SavingsTransaction::where('created_by_id', \$user_id)\n            ->where('savings_transaction_type_id', 1) // Deposit\n            ->whereDate('created_at', \$today)\n            ->where('reversed', 0)\n            ->sum('credit');\n            \n        \$today_withdrawals = SavingsTransaction::where('created_by_id', \$user_id)\n            ->where('savings_transaction_type_id', 2) // Withdrawal\n            ->whereDate('created_at', \$today)\n            ->where('reversed', 0)\n            ->sum('debit');\n            \n        \$today_count = SavingsTransaction::where('created_by_id', \$user_id)\n            ->whereDate('created_at', \$today)\n            ->where('reversed', 0)\n            ->count();\n            \n        // Calculate expected cash on hand (deposits - withdrawals)\n        \$expected_cash = \$today_deposits - \$today_withdrawals;\n        \n        return theme_view('teller::teller.index', compact('payment_types', 'today_deposits', 'today_withdrawals', 'today_count', 'expected_cash'));\n    }";

$content = str_replace($old_index, $new_index, $content);

// 2. Update deposit redirect
$content = str_replace(
    "Flash::success(trans_choice(\"core::general.successfully_saved\", 1) . ' - Deposit processed successfully');\n        return redirect('teller');",
    "Flash::success(trans_choice(\"core::general.successfully_saved\", 1) . ' - Deposit processed successfully');\n        return redirect('teller/receipt/' . \$savings_transaction->id);",
    $content
);

// 3. Update withdrawal redirect
$content = str_replace(
    "Flash::success(trans_choice(\"core::general.successfully_saved\", 1) . ' - Withdrawal processed successfully');\n        return redirect('teller');",
    "Flash::success(trans_choice(\"core::general.successfully_saved\", 1) . ' - Withdrawal processed successfully');\n        return redirect('teller/receipt/' . \$savings_transaction->id);",
    $content
);

// 4. Add receipt and print_receipt methods before the closing brace
$new_methods = "
    /**
     * Display transaction receipt
     */
    public function receipt(\$transaction_id)
    {
        \$transaction = SavingsTransaction::with(['savings', 'savings.client', 'savings.savings_product', 'savings.branch', 'savings.currency', 'created_by', 'payment_detail', 'payment_detail.payment_type'])->findOrFail(\$transaction_id);
        
        \$savings = \$transaction->savings;
        
        // Calculate new balance after this transaction
        \$new_balance = \$savings->transactions()
            ->where('reversed', 0)
            ->where('id', '<=', \$transaction_id)
            ->sum('credit') - \$savings->transactions()
            ->where('reversed', 0)
            ->where('id', '<=', \$transaction_id)
            ->sum('debit');
        
        return theme_view('teller::teller.receipt', compact('transaction', 'savings', 'new_balance'));
    }

    /**
     * Print transaction receipt
     */
    public function print_receipt(\$transaction_id)
    {
        \$transaction = SavingsTransaction::with(['savings', 'savings.client', 'savings.savings_product', 'savings.branch', 'savings.currency', 'created_by', 'payment_detail', 'payment_detail.payment_type'])->findOrFail(\$transaction_id);
        
        \$savings = \$transaction->savings;
        
        // Calculate new balance after this transaction
        \$new_balance = \$savings->transactions()
            ->where('reversed', 0)
            ->where('id', '<=', \$transaction_id)
            ->sum('credit') - \$savings->transactions()
            ->where('reversed', 0)
            ->where('id', '<=', \$transaction_id)
            ->sum('debit');
        
        return view('teller::themes.adminlte.teller.print_receipt', compact('transaction', 'savings', 'new_balance'));
    }
";

$content = str_replace("}\n", $new_methods . "}\n", $content);

file_put_contents($file, $content);

echo "✅ Controller updated successfully!\n";
