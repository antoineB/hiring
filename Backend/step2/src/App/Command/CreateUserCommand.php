<?php

declare(strict_types=1);

namespace Fulll\App\Command;

use Fulll\Domain\FleetRepositoryInterface;
use Fulll\Domain\UserRepositoryInterface;

final class CreateUserCommand implements CommandInterface
{
    private FleetRepositoryInterface $fleetRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository, UserRepositoryInterface $userRepository)
    {
        $this->fleetRepository = $fleetRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(int $userId): int
    {
        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            $user = $this->userRepository->create($userId);
        }
        $fleet = $this->fleetRepository->create($user);

        return $fleet->getId();
    }
}
