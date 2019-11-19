<?php


namespace App\Model;

class CategoryManager extends AbstractManager
{
    const TABLE = 'category';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAllCategory()
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ';')->fetchAll();
    }

    public function insertCategory(array $admin): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (`name`) VALUES (:name);");
        $statement->bindValue('name', $admin['nameCategory'], \PDO::PARAM_STR);
        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
