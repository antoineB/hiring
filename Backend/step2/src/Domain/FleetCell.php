<?php

declare(strict_types=1);

namespace Fulll\Domain;

final class FleetCell
{
    private Vehicle $vehicle;

    private ?Location $location = null;

    public function __construct(Vehicle $vehicle, ?Location $location = null)
    {
        $this->vehicle = $vehicle;
        $this->location = $location;
    }

    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * Imutable method to not leak internal representation in read-only.
     */
    public function setVehicle(Vehicle $vehicle): FleetCell
    {
        return new FleetCell($vehicle, $this->location);
    }

    public function setLocation(?Location $location): FleetCell
    {
        return new FleetCell($this->vehicle, $location);
    }
}
