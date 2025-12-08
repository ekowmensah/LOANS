<?php

namespace Modules\Client\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Client\Entities\Client;

class ClientStatusChanged
{
    use SerializesModels;

    public $client;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     *
     * @param Client $client
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Client $client, $oldStatus, $newStatus)
    {
        $this->client = $client;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
