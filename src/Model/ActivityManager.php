<?php


namespace App\Model;

class ActivityManager extends AbstractManager
{
    const TABLE = 'activity';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectActivityByType($type)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE type = :type;");
        $statement->bindValue('type', $type, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
