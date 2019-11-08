<?php


namespace App\Model;

class EventCarousselManager extends AbstractManager
{
    const IMGLIMIT = 3;
    public function selectPictureCaroussel()
    {
        return $this->pdo->query('SELECT image FROM ' . $this->table .
            ' WHERE caroussel=1 LIMIT ' . self::IMGLIMIT . ';')->fetchAll();
    }
}
