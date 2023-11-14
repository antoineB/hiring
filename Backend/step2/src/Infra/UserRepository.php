<?php

declare(strict_types=1);

namespace Fulll\Infra;

use Fulll\Domain\UserRepositoryInterface;
use Fulll\Domain\User;
use Fulll\Infra\Exception\SQLException;

class UserRepository implements UserRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $id): User
    {
        $sql = 'INSERT INTO user(id) VALUES(:id)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to create user");
        }

        return new User($id);
    }

    public function findById(int $id): ?User
    {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to fetch User");
        }
        $rows = $stmt->fetchAll();
        if (count($rows) === 0) {
            return null;
        }
        return new User($id);
    }
}
