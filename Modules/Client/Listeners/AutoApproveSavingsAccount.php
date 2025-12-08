<?php

namespace Modules\Client\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Client\Events\ClientStatusChanged;
use Modules\Savings\Entities\Savings;

class AutoApproveSavingsAccount
{
    /**
     * Handle the event.
     *
     * @param ClientStatusChanged $event
     * @return void
     */
    public function handle(ClientStatusChanged $event)
    {
        $client = $event->client;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        // Only proceed if client is being activated
        if ($newStatus !== 'active' || $oldStatus === 'active') {
            return;
        }

        try {
            // Find all submitted/pending savings accounts for this client
            $savingsAccounts = Savings::where('client_id', $client->id)
                ->whereIn('status', ['submitted', 'pending'])
                ->get();

            foreach ($savingsAccounts as $savings) {
                // Approve the savings account
                $savings->status = 'approved';
                $savings->approved_on_date = date('Y-m-d');
                $savings->approved_by_user_id = Auth::id();
                $savings->save();

                // Activate the savings account
                $savings->status = 'active';
                $savings->activated_on_date = date('Y-m-d');
                $savings->activated_by_user_id = Auth::id();
                $savings->save();

                activity()->on($savings)
                    ->withProperties(['id' => $savings->id, 'auto_approved' => true, 'client_id' => $client->id])
                    ->log('Auto-approved and Activated Savings Account when Client was Activated');

                Log::info('Auto-approved and activated savings account ' . $savings->account_number . ' for client: ' . $client->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to auto-approve savings accounts for client ' . $client->id . ': ' . $e->getMessage());
        }
    }
}
