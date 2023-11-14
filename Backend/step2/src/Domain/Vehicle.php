<?php

declare(strict_types=1);

namespace Fulll\Domain;

class Vehicle
{
    private int $id;

    private string $plateNumber;

    public function __construct(int $id, string $plateNumber)
    {
        if ($id < 1) {
            throw new \InvalidArgumentException("User id can't be 0 or negative");
        }

        if ($plateNumber === "") {
            throw new \InvalidArgumentException("The plate number can't be an empty string.");
        }

        $this->id = $id;
        $this->plateNumber = $plateNumber;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
