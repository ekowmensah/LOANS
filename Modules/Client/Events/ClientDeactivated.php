<?php

namespace Modules\Client\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Client\Entities\Client;

class ClientDeactivated
{
    use SerializesModels;

    public Client $client;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
