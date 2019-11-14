<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class AdminManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'event';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAllEvents(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' JOIN category c ON c.id = event.category_id
         JOIN representation r ON event.id = r.event_id')->fetchAll();
    }

    public function selectEventById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table JOIN category c ON c.id = event.category_id
         JOIN representation r ON event.id = r.event_id WHERE event.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectCategory(): array
    {
        return $this->pdo->query('SELECT name FROM category')->fetchAll();
    }

    public function selectRepresentation(): array
    {
        return $this->pdo->query('SELECT * FROM representation')->fetchAll();
    }
}
