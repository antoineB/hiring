<?php

declare(strict_types=1);

namespace Fulll\Infra;

use Fulll\App\Command\CommandInterface;
use Fulll\Domain\Exception\ExceptionInterface;

final class Cli
{
    private CommandInterface $createUser;

    private CommandInterface $registerVehicle;

    private CommandInterface $localizeVehicle;

    public function __construct(
        CommandInterface $createUser,
        CommandInterface $registerVehicle,
        CommandInterface $localizeVehicle
    ) {
        $this->createUser = $createUser;
        $this->registerVehicle = $registerVehicle;
        $this->localizeVehicle = $localizeVehicle;
    }

    private function printHelp(): void
    {
        echo <<<ECHO
            The possible forms are
            create <userId>
            register-vehicle <fleetId> <vehiclePlateNumber>
            localize-vehicle <fleetId> <vehiclePlateNumber> lat lng [alt]
            ECHO;
    }

    /**
     * @param string[] $argv Arguments with the script name
     */
    public function handle(array $argv): bool
    {
        if (count($argv) < 3) {
            $this->printHelp();
            return false;
        }

        try {
            switch ($argv[1]) {
                case 'create':
                    $fleetId = $this->createUser->handle(intval($argv[2]));
                    echo 'FleetId: ' . $fleetId . PHP_EOL;
                    return true;
                case 'register-vehicle':
                    if (!array_key_exists(3, $argv)) {
                        $this->printHelp();
                        return false;
                    }
                    $this->registerVehicle->handle(
                        intval($argv[2]),
                        $argv[3]
                    );
                    return true;
                case 'localize-vehicle':
                    if (!array_key_exists(3, $argv) || !array_key_exists(4, $argv) || !array_key_exists(5, $argv)) {
                        $this->printHelp();
                        return false;
                    }
                    if (array_key_exists(6, $argv)) {
                        $this->localizeVehicle->handle(
                            intval($argv[2]),
                            $argv[3],
                            floatval($argv[4]),
                            floatval($argv[5]),
                            floatval($argv[6])
                        );
                    } else {
                        $this->localizeVehicle->handle(
                            intval($argv[2]),
                            $argv[3],
                            floatval($argv[4]),
                            floatval($argv[5]),
                        );
                    }
                    return true;
                default:
                    $this->printHelp();
                    return false;
            }
        } catch (ExceptionInterface $e) {
            echo $e->getMessage() . PHP_EOL;
            return false;
        }
    }
}
