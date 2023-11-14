<?php

namespace Fulll\Domain\Exception;

class VehicleAlreadyRegisteredException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The vehicle is already registered");
    }
}
