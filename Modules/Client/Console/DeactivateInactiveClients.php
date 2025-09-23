<?php

namespace Modules\Client\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Modules\Client\Entities\Client;
use Modules\Client\Events\ClientDeactivated;
use Modules\Setting\Entities\Setting;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeactivateInactiveClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'clients:deactivate-inactive-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates clients who have not been active after some time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inactiveDays = Setting::where('setting_key', 'client.inactive_days')->first()->setting_value ?? 0;
        if (!empty($inactiveDays)) {
            $clients = Client::where('status', 'active')
                ->whereHas('loans', function (Builder $query) use ($inactiveDays) {
                    $query->where('status', 'active');
                    $query->orWhere('created_at', '>=', Carbon::today()->subDays($inactiveDays)->format('Y-m-d'));
                }, '=', 0)
                ->get();
            foreach ($clients as $client) {
                $client->status = 'inactive';
                $client->save();
                event(new ClientDeactivated($client));
            }
        }

        return 0;
    }


}
