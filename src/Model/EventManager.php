<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 05/11/19
 * Time: 17:34
 */

namespace App\Model;

class EventManager extends AbstractManager
{
    public function showEventHomePage()
    {
        return $this->pdo->query('SELECT image, title, datetime, name FROM ' . $this->table . '
        LEFT JOIN category ON category.id = id_category
        RIGHT JOIN representation ON representation.id = event_id')->fetchAll();
    }
}
