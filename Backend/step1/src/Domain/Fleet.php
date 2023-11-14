<?php

declare(strict_types=1);

namespace Fulll\Domain;

use Fulll\Domain\Exception\VehicleAlreadyRegisteredException;
use Fulll\Domain\Exception\VehicleNotRegisteredException;
use Fulll\Domain\Exception\VehicleUnparkedException;
use Fulll\Domain\Exception\VehicleAlreadyParkedAtLocationException;

final class Fleet
{
    /**
     * Un dictionnaire <Vehcile, Location>.
     *
     * @var \SplObjectStorage<Vehicle, ?Location>
     */
    private \SplObjectStorage $vehicles;

    public function __construct()
    {
        $this->vehicles = new \SplObjectStorage();
    }

    public function registerVehicle(Vehicle $vehicle): void
    {
        if ($this->vehicles->contains($vehicle)) {
            throw new VehicleAlreadyRegisteredException();
        }

        $this->vehicles[$vehicle] = null;
    }

    public function isVehicleRegistered(Vehicle $vehicle): bool
    {
        return isset($this->vehicles[$vehicle]);
    }

    public function parkVehicle(Vehicle $vehicle, Location $location): void
    {
        if (!$this->isVehicleRegistered($vehicle)) {
            throw new VehicleNotRegisteredException();
        }

        $previousLocation = $this->vehicles[$vehicle];
        if ($previousLocation === $location) {
            throw new VehicleAlreadyParkedAtLocationException();
        }

        $this->vehicles[$vehicle] = $location;
    }

    public function getVehicleLocation(Vehicle $vehicle): Location
    {
        if (!$this->isVehicleRegistered($vehicle)) {
            throw new VehicleNotRegisteredException();
        }

        $location = $this->vehicles[$vehicle];
        if ($location === null) {
            throw new VehicleUnparkedException();
        }

        return $location;
    }
}
