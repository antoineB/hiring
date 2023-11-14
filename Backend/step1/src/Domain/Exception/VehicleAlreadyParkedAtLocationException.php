<?php

namespace Fulll\Domain\Exception;

class VehicleAlreadyParkedAtLocationException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The vehicle is already park at the location");
    }
}
