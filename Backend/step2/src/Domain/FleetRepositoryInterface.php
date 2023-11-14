<?php

declare(strict_types=1);

namespace Fulll\Domain;

interface FleetRepositoryInterface
{
    public function create(User $user): Fleet;

    public function findById(int $id): ?Fleet;

    public function save(Fleet $fleet): void;
}
