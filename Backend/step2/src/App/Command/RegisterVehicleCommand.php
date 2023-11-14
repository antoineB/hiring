<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\Exception\FleetNotFoundException;
use Fulll\Domain\FleetRepositoryInterface;
use Fulll\Domain\VehicleRepositoryInterface;

final class RegisterVehicleCommand implements CommandInterface
{
    private FleetRepositoryInterface $fleetRepository;

    private VehicleRepositoryInterface $vehicleRepository;

    public function __construct(
        FleetRepositoryInterface $fleetRepository,
        VehicleRepositoryInterface $vehicleRepository
    ) {
        $this->fleetRepository = $fleetRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(int $fleetId, string $vehiclePlateNumber): bool
    {
        $fleet = $this->fleetRepository->findById($fleetId);

        if ($fleet === null) {
            throw new FleetNotFoundException();
        }

        $vehicle = $this->vehicleRepository->findByPlateNumber($vehiclePlateNumber);
        if ($vehicle === null) {
            $vehicle = $this->vehicleRepository->create($vehiclePlateNumber);
        }
        $fleet->registerVehicle($vehicle);
        $this->fleetRepository->save($fleet);

        return true;
    }
}
