<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TariffCreated
{
    use Dispatchable, SerializesModels;

    /**
     * The tariff ID.
     *
     * @var int
     */
    public $tariffId;

    /**
     * Create a new event instance.
     *
     * @param int $tariffId
     */
    public function __construct(int $tariffId)
    {
        $this->tariffId = $tariffId;
    }
}
