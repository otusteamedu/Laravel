<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tariff\Models\Tariff;

class TariffUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * The tariff instance.
     *
     * @var \Tariff\Models\Tariff
     */
    public $tariff;

    /**
     * Create a new event instance.
     *
     * @param \Tariff\Models\Tariff $tariff
     */
    public function __construct(Tariff $tariff)
    {
        $this->tariff = $tariff;
    }
}
