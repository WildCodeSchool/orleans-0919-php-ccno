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

    public function selectAllCategory()
    {
        return $this->pdo->query('SELECT * FROM category')->fetchAll();
    }

    /**
     * @param array $admin
     * @return int
     */
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

    /**
     * @param array $admin
     * @return int
     */

    public function insertCategory(array $admin): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO `ccno`.`category` (`name`) VALUES (:name);");
        $statement->bindValue('name', $admin['nameCategory'], \PDO::PARAM_STR);
        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param array $admin
     * @return bool
     */
    public function update(array $admin):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $admin['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $admin['title'], \PDO::PARAM_STR);

        return $statement->execute();
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
