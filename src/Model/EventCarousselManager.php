<?php


namespace App\Model;

class EventCarousselManager extends AbstractManager
{
    public function selectPictureCaroussel()
    {
        return $this->pdo->query('SELECT image FROM ' . $this->table . ' WHERE caroussel=1 LIMIT 3;')->fetchAll();
    }
}
