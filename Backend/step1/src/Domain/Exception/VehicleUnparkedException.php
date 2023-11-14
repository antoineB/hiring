<?php

namespace Fulll\Domain\Exception;

class VehicleUnparkedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The vehicle has not been parked.");
    }
}
