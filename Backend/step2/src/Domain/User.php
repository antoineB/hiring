<?php

declare(strict_types=1);

namespace Fulll\Domain;

class User
{
    private int $id;

    public function __construct(int $id)
    {
        if ($id < 1) {
            throw new \InvalidArgumentException("User id can't be 0 or negative");
        }

        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
