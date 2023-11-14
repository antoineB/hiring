<?php

namespace Fulll\Domain\Exception;

class FleetNotFoundException extends \Exception implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct("Fleet not found.");
    }
}
