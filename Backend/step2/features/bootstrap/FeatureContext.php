<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\Domain\Fleet;
use Fulll\Domain\Location;
use Fulll\Domain\Vehicle;
use Fulll\Domain\User;
use Fulll\Domain\Exception\VehicleAlreadyRegisteredException;
use Fulll\Domain\Exception\VehicleAlreadyParkedAtLocationException;


class FeatureContext implements Context
{
    /**
     * @var Fleet $myFleet
     */
    private $myFleet;

    /**
     * @var Vehicle $aVehicle
     */
    private $aVehicle;

    private $vehicleAlreadyRegistegedIntoMyFleet = false;

    private $parkedAtSameLocationException = false;

    /**
     * @var Fleet
     */
    private $anotherFleet;

    /**
     * @var Location
     */
    private $aLocation;

    /**
     * @var Location
     */
    private $anotherLocation;

    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $this->myFleet = new Fleet(1, new User(1));
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->aVehicle = new Vehicle(1, "aVehicle");
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet()
    {
        $this->myFleet->registerVehicle($this->aVehicle);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        if (!$this->myFleet->isVehicleRegistered($this->aVehicle)) {
            throw new \RuntimeException("A vehicle should be registered into the fleet");
        }
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        $this->iRegisterThisVehicleIntoMyFleet();
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet()
    {
        try {
            $this->iRegisterThisVehicleIntoMyFleet();
        } catch (VehicleAlreadyRegisteredException $_) {
            $this->vehicleAlreadyRegistegedIntoMyFleet = true;
        }
    }

    /**
     * @Then I should be informed this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet()
    {
        if (!$this->vehicleAlreadyRegistegedIntoMyFleet) {
            throw new \RuntimeException("Register an already registered vehicle should throw an exception.");
        }
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $this->anotherFleet = new Fleet(2, new User(2));
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $this->anotherFleet->registerVehicle($this->aVehicle);
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $this->aLocation = new Location(50, 50);
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        $this->myFleet->parkVehicle($this->aVehicle, $this->aLocation);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $location = $this->myFleet->getVehicleLocation($this->aVehicle);
        if ($this->aLocation != $location) {
            throw new \RuntimeException("The parked location should match the used location.");
        }
    }

    /**
     * @Given my vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        $this->iParkMyVehicleAtThisLocation();
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation()
    {
        try {
            $this->iParkMyVehicleAtThisLocation();
        } catch (VehicleAlreadyParkedAtLocationException $_) {
            $this->parkedAtSameLocationException = true;
        }
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        if (!$this->parkedAtSameLocationException) {
            throw new \RuntimeException("Park a vehicle at the same location should throw an exception.");
        }
    }

    /**
     * @Given another location
     */
    public function anotherLocation()
    {
        $this->anotherLocation = new Location(25, 25);
    }

    /**
     * @When I park my vehicle at the other location
     */
    public function iParkMyVehicleAtTheOtherLocation()
    {
        $this->myFleet->parkVehicle($this->aVehicle, $this->anotherLocation);
    }

    /**
     * @Then the known location of my vehicle should verify the other location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyTheOtherLocation()
    {
        $location = $this->myFleet->getVehicleLocation($this->aVehicle);
        if ($this->anotherLocation != $location) {
            throw new \RuntimeException("The parked location should match the used location.");
        }
    }
}
