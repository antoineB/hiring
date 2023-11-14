<?php

require_once __DIR__ . '/vendor/autoload.php';

use Fulll\App\Command\CreateUserCommand;
use Fulll\App\Command\LocalizeVehicleCommand;
use Fulll\App\Command\RegisterVehicleCommand;
use Fulll\Infra\Cli;
use Fulll\Infra\FleetRepository;
use Fulll\Infra\UserRepository;
use Fulll\Infra\VehicleRepository;

$pdo = new PDO('sqlite:sqlite.db');

$vehicleRepository = new VehicleRepository($pdo);
$userRepository = new UserRepository($pdo);
$fleetRepository = new FleetRepository($pdo);

$createUserCommand = new CreateUserCommand($fleetRepository, $userRepository);
$registerVehicleCommand = new RegisterVehicleCommand($fleetRepository, $vehicleRepository);
$localizeVehicleCommand = new LocalizeVehicleCommand($fleetRepository);

$cli = new Cli($createUserCommand, $registerVehicleCommand, $localizeVehicleCommand);

if ($cli->handle($argv)) {
    exit(0);
}

exit(1);