<?php

declare(strict_types=1);

namespace Fulll\Domain;

interface VehicleRepositoryInterface
{
    public function create(string $plateNumber): Vehicle;

    public function findByPlateNumber(string $plateNumber): ?Vehicle;
}
