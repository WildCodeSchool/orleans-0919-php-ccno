<?php


namespace App\Model;

class AdminManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'representation';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAllCategory()
    {
        return $this->pdo->query('SELECT name FROM category')->fetchAll();
    }

    /**
     * @param array $admin
     * @return int
     */
    public function insertEvent(array $admin): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        (title, image, description, ccno, caroussel, category_id, price, place, datetime, duration, event_id) 
        VALUES (:title)");
        $statement->bindValue('title', $admin['title'], \PDO::PARAM_STR);

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
}
