<?php

namespace Modules\Teller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Modules\Accounting\Entities\JournalEntry;
use Modules\Core\Entities\PaymentDetail;
use Modules\Core\Entities\PaymentType;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Savings\Events\TransactionUpdated;

class TellerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:teller.teller.index'])->only(['index']);
        $this->middleware(['permission:teller.teller.transactions.create'])->only(['search_account', 'process_transaction']);
    }

    /**
     * Display teller dashboard
     * @return Response
     */
    public function index()
    {
        $payment_types = PaymentType::where('active', 1)->get();
        return theme_view('teller::teller.index', compact('payment_types'));
    }

    /**
     * Search for savings account by account number (AJAX)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search_account(Request $request)
    {
        $account_number = $request->account_number;
        
        if (empty($account_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter an account number'
            ], 400);
        }

        $savings = Savings::with(['client', 'savings_product', 'branch', 'currency', 'savings_officer'])
            ->where('account_number', $account_number)
            ->first();

        if (!$savings) {
            return response()->json([
                'success' => false,
                'message' => 'Account not found'
            ], 404);
        }

        // Check if account is active
        if ($savings->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Account is not active. Status: ' . ucfirst($savings->status)
            ], 400);
        }

        // Calculate balance
        $balance = $savings->transactions->where('reversed', 0)->sum('credit') - $savings->transactions->where('reversed', 0)->sum('debit');

        return response()->json([
            'success' => true,
            'data' => [
                'savings_id' => $savings->id,
                'account_number' => $savings->account_number,
                'client_name' => $savings->client->first_name . ' ' . $savings->client->last_name,
                'client_mobile' => $savings->client->mobile,
                'client_photo' => $savings->client->photo,
                'product_name' => $savings->savings_product->name,
                'branch_name' => $savings->branch->name,
                'currency' => $savings->currency->name,
                'currency_symbol' => $savings->currency->symbol,
                'balance' => number_format($balance, $savings->decimals),
                'raw_balance' => $balance,
                'decimals' => $savings->decimals,
                'allow_overdraft' => $savings->savings_product->allow_overdraft,
                'overdraft_limit' => $savings->savings_product->overdraft_limit,
                'status' => $savings->status,
            ]
        ]);
    }

    /**
     * Process deposit or withdrawal transaction
     * @param Request $request
     * @return Response
     */
    public function process_transaction(Request $request)
    {
        $request->validate([
            'savings_id' => ['required', 'exists:savings,id'],
            'transaction_type' => ['required', 'in:deposit,withdrawal'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'payment_type_id' => ['required', 'exists:payment_types,id'],
        ]);

        $savings = Savings::with('savings_product')->find($request->savings_id);

        // Validate account status
        if ($savings->status !== 'active') {
            Flash::warning('Account is not active');
            return redirect()->back()->withInput();
        }

        if ($request->transaction_type === 'withdrawal') {
            return $this->process_withdrawal($request, $savings);
        } else {
            return $this->process_deposit($request, $savings);
        }
    }

    /**
     * Process deposit transaction
     */
    private function process_deposit(Request $request, Savings $savings)
    {
        // Payment details
        $payment_detail = new PaymentDetail();
        $payment_detail->created_by_id = Auth::id();
        $payment_detail->payment_type_id = $request->payment_type_id;
        $payment_detail->transaction_type = 'savings_transaction';
        $payment_detail->cheque_number = $request->cheque_number;
        $payment_detail->receipt = $request->receipt;
        $payment_detail->account_number = $request->payment_account_number;
        $payment_detail->bank_name = $request->bank_name;
        $payment_detail->routing_code = $request->routing_code;
        $payment_detail->save();

        // Create savings transaction
        $savings_transaction = new SavingsTransaction();
        $savings_transaction->created_by_id = Auth::id();
        $savings_transaction->savings_id = $savings->id;
        $savings_transaction->branch_id = $savings->branch_id;
        $savings_transaction->payment_detail_id = $payment_detail->id;
        $savings_transaction->name = trans_choice('savings::general.deposit', 1);
        $savings_transaction->savings_transaction_type_id = 1;
        $savings_transaction->submitted_on = $request->date;
        $savings_transaction->created_on = date("Y-m-d");
        $savings_transaction->reversible = 1;
        $savings_transaction->amount = $request->amount;
        $savings_transaction->credit = $request->amount;
        $savings_transaction->save();

        // Journal entries for cash accounting
        if ($savings->savings_product->accounting_rule == 'cash') {
            // Credit account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_control_chart_of_account_id;
            $journal_entry->transaction_type = 'savings_deposit';
            $journal_entry->date = $request->date;
            $date = explode('-', $request->date);
            $journal_entry->month = $date[1];
            $journal_entry->year = $date[0];
            $journal_entry->credit = $savings_transaction->amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->save();

            // Debit account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_reference_chart_of_account_id;
            $journal_entry->transaction_type = 'savings_deposit';
            $journal_entry->date = $request->date;
            $date = explode('-', $request->date);
            $journal_entry->month = $date[1];
            $journal_entry->year = $date[0];
            $journal_entry->debit = $savings_transaction->amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->save();
        }

        activity()->on($savings)
            ->withProperties(['id' => $savings->id])
            ->log('Teller Deposit');

        // Fire transaction updated event
        event(new TransactionUpdated($savings));

        Flash::success(trans_choice("core::general.successfully_saved", 1) . ' - Deposit processed successfully');
        return redirect('teller');
    }

    /**
     * Process withdrawal transaction
     */
    private function process_withdrawal(Request $request, Savings $savings)
    {
        // Calculate balance
        $balance = $savings->transactions->where('reversed', 0)->sum('credit') - $savings->transactions->where('reversed', 0)->sum('debit');

        // Check sufficient balance
        if ($request->amount > $balance && $savings->savings_product->allow_overdraft == 0) {
            Flash::warning(trans_choice("savings::general.insufficient_balance", 1));
            return redirect()->back()->withInput();
        }

        // Check overdraft limit
        if ($request->amount > $balance && $savings->savings_product->allow_overdraft == 1 && $request->amount > $savings->savings_product->overdraft_limit) {
            Flash::warning(trans_choice("savings::general.insufficient_overdraft_balance", 1));
            return redirect()->back()->withInput();
        }

        // Payment details
        $payment_detail = new PaymentDetail();
        $payment_detail->created_by_id = Auth::id();
        $payment_detail->payment_type_id = $request->payment_type_id;
        $payment_detail->transaction_type = 'savings_transaction';
        $payment_detail->cheque_number = $request->cheque_number;
        $payment_detail->receipt = $request->receipt;
        $payment_detail->account_number = $request->payment_account_number;
        $payment_detail->bank_name = $request->bank_name;
        $payment_detail->routing_code = $request->routing_code;
        $payment_detail->save();

        // Create savings transaction
        $savings_transaction = new SavingsTransaction();
        $savings_transaction->created_by_id = Auth::id();
        $savings_transaction->savings_id = $savings->id;
        $savings_transaction->branch_id = $savings->branch_id;
        $savings_transaction->payment_detail_id = $payment_detail->id;
        $savings_transaction->name = trans_choice('savings::general.withdrawal', 1);
        $savings_transaction->savings_transaction_type_id = 2;
        $savings_transaction->submitted_on = $request->date;
        $savings_transaction->created_on = date("Y-m-d");
        $savings_transaction->reversible = 1;
        $savings_transaction->amount = $request->amount;
        $savings_transaction->debit = $request->amount;
        $savings_transaction->save();

        // Journal entries for cash accounting
        if ($savings->savings_product->accounting_rule == 'cash') {
            // Credit account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_reference_chart_of_account_id;
            $journal_entry->transaction_type = 'savings_withdrawal';
            $journal_entry->date = $request->date;
            $date = explode('-', $request->date);
            $journal_entry->month = $date[1];
            $journal_entry->year = $date[0];
            $journal_entry->credit = $savings_transaction->amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->save();

            // Debit account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_control_chart_of_account_id;
            $journal_entry->transaction_type = 'savings_withdrawal';
            $journal_entry->date = $request->date;
            $date = explode('-', $request->date);
            $journal_entry->month = $date[1];
            $journal_entry->year = $date[0];
            $journal_entry->debit = $savings_transaction->amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->save();
        }

        activity()->on($savings)
            ->withProperties(['id' => $savings->id])
            ->log('Teller Withdrawal');

        // Fire transaction updated event
        event(new TransactionUpdated($savings));

        Flash::success(trans_choice("core::general.successfully_saved", 1) . ' - Withdrawal processed successfully');
        return redirect('teller');
    }
}
