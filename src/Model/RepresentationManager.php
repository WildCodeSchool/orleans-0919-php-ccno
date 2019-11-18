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
        join event e on e.id = r.event_id WHERE r.id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
