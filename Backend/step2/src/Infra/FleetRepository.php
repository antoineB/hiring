<?php

declare(strict_types=1);

namespace Fulll\Infra;

use Fulll\Domain\FleetRepositoryInterface;
use Fulll\Domain\Fleet;
use Fulll\Domain\Vehicle;
use Fulll\Domain\Location;
use Fulll\Domain\User;
use Fulll\Infra\Exception\SQLException;

class FleetRepository implements FleetRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(User $user): Fleet
    {
        $sql = 'INSERT INTO fleet(user_id) VALUES(:user_id) RETURNING id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user->getId(), \PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to create Fleet");
        }
        $row = $stmt->fetch();
        return new Fleet($row['id'], $user);
    }

    public function findById(int $id): ?Fleet
    {
        $sql = <<<'SQL'
             SELECT f.id, f.user_id, fvl.vehicle_id, v.plate_number, fvl.latitude, fvl.longitude, fvl.altitude
             FROM fleet f
             JOIN user u ON u.id = f.user_id
             LEFT JOIN fleet_vehicle_location fvl ON fvl.fleet_id = f.id
             LEFT JOIN vehicle v ON fvl.vehicle_id = v.id
             WHERE f.id = :id
             SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new SQLException("Impossible to fetch Fleet");
        }
        $rows = $stmt->fetchAll();
        if (count($rows) === 0) {
            return null;
        }
        $user = new User($rows[0]['user_id']);
        $fleet = new Fleet($rows[0]['id'], $user);
        foreach ($rows as $row) {
            if ($row['vehicle_id'] !== null) {
                $vehicle = new Vehicle($row['vehicle_id'], $row['plate_number']);
                $fleet->registerVehicle($vehicle);
                if ($row['latitude'] !== null) {
                    $location = new Location(
                        $row['latitude'],
                        $row['longitude'],
                        $row['altitude']
                    );
                    $fleet->parkVehicle($vehicle, $location);
                }
            }
        }

        return $fleet;
    }

    public function save(Fleet $fleet): void
    {
        $this->pdo->beginTransaction();
        $sql = 'DELETE FROM fleet_vehicle_location WHERE fleet_id = :fleet_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':fleet_id', $fleet->getId(), \PDO::PARAM_INT);
        $stmt->execute();

        $sql = <<<'SQL'
             INSERT INTO fleet_vehicle_location(fleet_id, vehicle_id, latitude, longitude, altitude)
             VALUES (:fleet_id, :vehicle_id, :latitude, :longitude, :altitude)
             SQL;
        foreach ($fleet->getCells() as $cell) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':fleet_id', $fleet->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':vehicle_id', $cell->getVehicle()->getId(), \PDO::PARAM_INT);
            if ($cell->getLocation() === null) {
                $stmt->bindValue(':latitude', 'null', \PDO::PARAM_NULL);
                $stmt->bindValue(':longitude', 'null', \PDO::PARAM_NULL);
                $stmt->bindValue(':altitude', 'null', \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':latitude', $cell->getLocation()->getLatitude());
                $stmt->bindValue(':longitude', $cell->getLocation()->getLongitude());
                $stmt->bindValue(':altitude', $cell->getLocation()->getAltitude());
            }
            if ($stmt->execute() === false) {
                $this->pdo->rollBack();
            }
        }

        $this->pdo->commit();
    }
}
