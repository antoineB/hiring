<?php

declare(strict_types=1);

namespace Fulll\Domain;

use Fulll\Domain\Exception\VehicleAlreadyRegisteredException;

/**
 * Immutable object of a gps location
 */
final class Location
{
    private float $latitude;

    private float $longitude;

    private float $altitude;

    public function __construct(float $latitude, float $longitude, float $altitude = 0.0)
    {
        if ($latitude < -90.0) {
            throw new \InvalidArgumentException("The latitude degree must be between -90° and +90°.");
        }
        if ($latitude > 90.0) {
            throw new \InvalidArgumentException("The latitude degree must be between -90° and +90°.");
        }

        if ($longitude < -180.0) {
            throw new \InvalidArgumentException("The longitude degree must be between -180° and +180°.");
        }
        if ($longitude > 180.0) {
            throw new \InvalidArgumentException("The longitude degree must be between -180° and +180°.");
        }

        if ($altitude < 0.0) {
            throw new \InvalidArgumentException("The altitude must be a positive number.");
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getAltitude(): float
    {
        return $this->altitude;
    }
}
