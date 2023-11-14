<?php

declare(strict_types=1);

namespace Fulll\Domain;

/**
 * Immutable object of a gps location
 */
final class Location
{
    private float $latitude;

    private float $longitude;

    public function __construct(float $latitude, float $longitude)
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

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
