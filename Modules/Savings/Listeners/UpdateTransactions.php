<?php

namespace Modules\Savings\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTransactions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $savings = $event->savings;
        $balance = 0;
        
        // Only process non-reversed transactions
        foreach ($savings->transactions()->where('reversed', 0)->orderBy('submitted_on')->orderBy('id')->get() as $savings_transaction) {
            $balance = $balance - $savings_transaction->debit + $savings_transaction->credit;
            $savings_transaction->balance = $balance;
            $savings_transaction->save();
        }
        
        $savings->balance_derived = $balance;
        $savings->save();
    }
}
