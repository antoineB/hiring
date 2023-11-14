<?php

namespace Fulll\Domain\Exception;

class VehicleAlreadyParkedAtLocationException extends \Exception implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct("The vehicle is already park at the location");
    }
}
