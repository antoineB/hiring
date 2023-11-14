<?php

namespace Fulll\Domain\Exception;

class VehicleNotRegisteredException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The vehicle is not registered");
    }
}
