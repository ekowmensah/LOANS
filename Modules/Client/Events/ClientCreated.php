<?php

namespace Modules\Client\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Client\Entities\Client;

class ClientCreated
{
    use SerializesModels;

    public $client;

    /**
     * Create a new event instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
