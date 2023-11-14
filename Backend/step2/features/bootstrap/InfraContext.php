<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\Domain\Fleet;
use Fulll\Domain\Location;
use Fulll\Domain\Vehicle;
use Fulll\Domain\User;
use Fulll\Domain\Exception\VehicleAlreadyRegisteredException;
use Fulll\Domain\Exception\VehicleAlreadyParkedAtLocationException;
use Fulll\Infra\FleetRepository;
use Fulll\Infra\VehicleRepository;
use Fulll\Infra\UserRepository;

class InfraContext implements Context
{
    private Fleet $myFleet;

    private Vehicle $aVehicle;

    private FleetRepository $fleetRepository;

    private UserRepository $userRepository;

    private VehicleRepository $vehicleRepository;

    private Location $aLocation;

    private Vehicle $anotherVehicle;

    function __construct()
    {
        copy(__DIR__. '/template.db', __DIR__ . '/test.db');

        $pdo = new PDO('sqlite:' . __DIR__ . '/test.db');
        $this->fleetRepository = new FleetRepository($pdo);
        $this->vehicleRepository = new VehicleRepository($pdo);
        $this->userRepository = new UserRepository($pdo);

        register_shutdown_function(
            function () {
                if (file_exists(__DIR__ . '/test.db')) {
                    unlink(__DIR__ . '/test.db');
                }
            }
        );
    }

    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $user = $this->userRepository->create(1);
        $this->myFleet = $this->fleetRepository->create($user);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->aVehicle = $this->vehicleRepository->create("aVehicle");
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        $this->myFleet->registerVehicle($this->aVehicle);
        $this->fleetRepository->save($this->myFleet);
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
        $this->fleetRepository->save($this->myFleet);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $fleet = $this->fleetRepository->findById($this->myFleet->getId());
        $location = $fleet->getVehicleLocation($this->aVehicle);

        if ($location != $this->aLocation) {
            throw new \RuntimeException('The vehicle location should be the same as the $aLocation.');
        }
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet()
    {
        $this->anotherVehicle = $this->vehicleRepository->create("anotherVehicle");
        $this->myFleet->registerVehicle($this->anotherVehicle);
        $this->fleetRepository->save($this->myFleet);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $fleet = $this->fleetRepository->findById($this->myFleet->getId());
        if (!$fleet->isVehicleRegistered($this->anotherVehicle)) {
            throw new \RuntimeException('The $anotherVehicle should be registered into $myFleet.');
        }
    }
}
