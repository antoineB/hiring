<?php

declare(strict_types=1);

namespace Fulll\Domain;

interface UserRepositoryInterface
{
    public function create(int $id): User;

    public function findById(int $id): ?User;
}
