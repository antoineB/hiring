<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Exception\FleetNotFoundException;
use Fulll\Domain\FleetRepositoryInterface;
use Fulll\Domain\Location;

final class LocalizeVehicleCommand implements CommandInterface
{
    private FleetRepositoryInterface $fleetRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository)
    {
        $this->fleetRepository = $fleetRepository;
    }

    public function handle(
        int $fleetId,
        string $vehiclePlateNumber,
        float $latitude,
        float $longitude,
        ?float $altitude = null
    ): bool {
        $fleet = $this->fleetRepository->findById($fleetId);

        if ($fleet === null) {
            throw new FleetNotFoundException();
        }

        $vehicle = $fleet->getVehicle($vehiclePlateNumber);

        $fleet->parkVehicle(
            $vehicle,
            $altitude === null
                ? new Location($latitude, $longitude)
                : new Location($latitude, $longitude, $altitude)
        );
        $this->fleetRepository->save($fleet);

        return true;
    }
}
