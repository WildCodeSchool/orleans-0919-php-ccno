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

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
