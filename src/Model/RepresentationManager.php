<?php


namespace App\Model;

class RepresentationManager extends AbstractManager
{
    const TABLE = 'representation';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function showOne($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM representation r 
         WHERE event_id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function addRepresentation(array $admin): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . $this->table . "
        (price, place, datetime, duration, event_id) VALUES
        (:price, :place, :datetime, :duration, :event_id)
        ");
        $statement->bindValue('price', $admin['price'], \PDO::PARAM_STR);
        $statement->bindValue('place', $admin['place'], \PDO::PARAM_STR);
        $statement->bindValue('datetime', $admin['datetime'], \PDO::PARAM_STR);
        $statement->bindValue('duration', $admin['duration'], \PDO::PARAM_STR);
        $statement->bindValue('event_id', $admin['event_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function editRepresentation(array $admin): int
    {
        $statement = $this->pdo->prepare("UPDATE " .  $this->table . " 
        SET price=:price, place=:place, `datetime`=:datetime, duration=:duration WHERE id=:id ;");
        $statement->bindValue('price', $admin['price'], \PDO::PARAM_STR);
        $statement->bindValue('id', $admin['id'], \PDO::PARAM_INT);
        $statement->bindValue('place', $admin['place'], \PDO::PARAM_STR);
        $statement->bindValue('datetime', $admin['datetime'], \PDO::PARAM_STR);
        $statement->bindValue('duration', $admin['duration'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
