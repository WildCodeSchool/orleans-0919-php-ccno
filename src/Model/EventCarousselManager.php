<?php


namespace App\Model;

class EventCarousselManager extends AbstractManager
{
    const IMGLIMIT = 3;
    const TABLE = 'event';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectPictureCaroussel()
    {
        return $this->pdo->query('SELECT image, title FROM ' . $this->table .
            ' WHERE caroussel=1 LIMIT ' . self::IMGLIMIT . ';')->fetchAll();
    }
}
