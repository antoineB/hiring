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
     * Un ensemble de véhicles.
     *
     * @var array<string, FleetCell>
     */
    private array $vehicles = [];

    private User $user;

    private int $id;

    public function __construct(int $id, User $user)
    {
        if ($id < 1) {
            throw new \InvalidArgumentException("Id can't be 0 or negative");
        }

        $this->id = $id;
        $this->user = $user;
    }

    public function registerVehicle(Vehicle $vehicle): void
    {
        $plateNumber = $vehicle->getPlateNumber();
        if (array_key_exists($plateNumber, $this->vehicles)) {
            throw new VehicleAlreadyRegisteredException();
        }

        $cell = new FleetCell($vehicle);
        $this->vehicles[$plateNumber] = $cell;
    }

    public function isVehicleRegistered(Vehicle $vehicle): bool
    {
        return array_key_exists($vehicle->getPlateNumber(), $this->vehicles);
    }

    public function parkVehicle(Vehicle $vehicle, Location $location): void
    {
        if (!$this->isVehicleRegistered($vehicle)) {
            throw new VehicleNotRegisteredException();
        }

        $plateNumber = $vehicle->getPlateNumber();
        $cell = $this->vehicles[$plateNumber];
        if ($cell->getLocation() === $location) {
            throw new VehicleAlreadyParkedAtLocationException();
        }

        $cell = $cell->setLocation($location);

        $this->vehicles[$plateNumber] = $cell;
    }

    public function getVehicleLocation(Vehicle $vehicle): Location
    {
        if (!$this->isVehicleRegistered($vehicle)) {
            throw new VehicleNotRegisteredException();
        }

        $cell = $this->vehicles[$vehicle->getPlateNumber()];
        $location = $cell->getLocation();
        if (!($location instanceof Location)) {
            throw new VehicleUnparkedException();
        }

        return $location;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVehicle(string $plateNumber): Vehicle
    {
        if (!array_key_exists($plateNumber, $this->vehicles)) {
            throw new VehicleNotRegisteredException();
        }

        return $this->vehicles[$plateNumber]->getVehicle();
    }

    /**
     * @return FleetCell[]
     */
    public function getCells(): array
    {
        return array_values($this->vehicles);
    }
}
