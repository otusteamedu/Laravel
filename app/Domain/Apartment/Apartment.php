<?php

namespace App\Domain\Apartment;

use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;

class Apartment
{
    private Owner $owner;
    private SerialNumber $serialNumber;
    private array $details = [];
    private array $fees = [];

    public function __construct(
        Owner $owner,
        SerialNumber $serialNumber,
        array $details = [],
        array $fees = []
    ) {
        $this->owner = $owner;
        $this->serialNumber = $serialNumber;
        $this->details = $details;
        $this->fees = $fees;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function getSerialNumber(): SerialNumber
    {
        return $this->serialNumber;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @return array
     */
    public function getFees(): array
    {
        return $this->fees;
    }
}
