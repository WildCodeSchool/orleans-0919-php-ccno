<?php


namespace App\Model;

class CategoryManager extends AbstractManager
{
    const TABLE = 'category';

    /**$this->table
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
}
