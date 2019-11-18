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
        return $this->pdo->query("SELECT * FROM $this->table")->fetchAll();
    }

    public function selectEventById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function updateEvent(array $data)
    {
        $statement = $this->pdo->prepare("UPDATE ". self::TABLE  ."
                SET title=:title, image=:image, description=:description, ccno=:ccno, caroussel=:caroussel           
                WHERE id=:id
            ");
        $statement->bindValue('title', $data['title'], \PDO::PARAM_STR);
        $statement->bindValue('image', $data['image'], \PDO::PARAM_STR);
        $statement->bindValue('description', $data['description'], \PDO::PARAM_STR);
        $statement->bindValue('ccno', $data['ccno'], \PDO::PARAM_BOOL);
        $statement->bindValue('caroussel', $data['caroussel'], \PDO::PARAM_BOOL);
        $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
