<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TariffDeleted
{
    use Dispatchable, SerializesModels;

    /**
     * The name of the deleted tariff.
     *
     * @var string
     */
    public string $tariffName;

    /**
     * Create a new event instance.
     *
     * @param string $tariffName
     */
    public function __construct(string $tariffName)
    {
        $this->tariffName = $tariffName;
    }
}
