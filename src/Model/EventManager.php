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
        return $this->pdo->query('SELECT image, title, datetime, name FROM ' . $this->table . ' e  
         JOIN category c ON c.id = e.category_id
         JOIN representation r ON e.id = r.event_id
         ORDER BY datetime
         LIMIT 6')->fetchAll();
    }
}
