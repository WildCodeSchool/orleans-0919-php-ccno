<?php


namespace App\Model;

class RepresentationManager extends AbstractManager
{
    const TABLE = 'representation';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAllRepresentations($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' r 
        JOIN event e ON e.id = r.event_id WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
