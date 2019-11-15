<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 05/11/19
 * Time: 17:34
 */

namespace App\Model;

use App\Model\CategoryManager;

class EventManager extends AbstractManager
{
    const NUMBERPICTURE = 6;
    const TABLE = 'event';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function showEventHomePage()
    {
        return $this->pdo->query('SELECT image, title, datetime, name FROM ' . $this->table . ' e  
         JOIN category c ON c.id = e.category_id
         JOIN representation r ON e.id = r.event_id
         ORDER BY datetime
         LIMIT ' . self::NUMBERPICTURE . ';')->fetchAll();
    }

    public function insertEvent(array $admin): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO `ccno`.`event` 
        (`title`, `image`, `description`, `ccno`, `caroussel`, `category_id`) 
        VALUES (:title, :image, :description, :ccno, :caroussel, :category_id);");

        $statement->bindValue('title', $admin['title'], \PDO::PARAM_STR);
        $statement->bindValue('image', $admin['image'], \PDO::PARAM_STR);
        $statement->bindValue('description', $admin['description'], \PDO::PARAM_STR);
        $statement->bindValue('ccno', $admin['ccno'], \PDO::PARAM_BOOL);
        $statement->bindValue('caroussel', $admin['caroussel'], \PDO::PARAM_BOOL);
        $statement->bindValue('category_id', $admin['category'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function selectAllEvents(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ;')->fetchAll();
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
}
