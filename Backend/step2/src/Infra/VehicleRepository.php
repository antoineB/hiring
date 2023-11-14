<?php

declare(strict_types=1);

namespace Fulll\Infra;

use Fulll\Domain\VehicleRepositoryInterface;
use Fulll\Domain\Vehicle;
use Fulll\Infra\Exception\SQLException;

class VehicleRepository implements VehicleRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $plateNumber): Vehicle
    {
        $sql = 'INSERT INTO vehicle(plate_number) VALUES(:plate_number) RETURNING *';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':plate_number', $plateNumber, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to create vehicle");
        }
        $row = $stmt->fetch();
        return new Vehicle($row['id'], $row['plate_number']);
    }

    public function findByPlateNumber(string $plateNumber): ?Vehicle
    {
        $sql = 'SELECT * FROM vehicle WHERE plate_number = :plate_number';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':plate_number', $plateNumber, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to fetch vehicle");
        }
        $rows = $stmt->fetchAll();
        if (count($rows) == 0) {
            return null;
        }
        $row = $rows[0];
        return new Vehicle($row['id'], $row['plate_number']);
    }
}
