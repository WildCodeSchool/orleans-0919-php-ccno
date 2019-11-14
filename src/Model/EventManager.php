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
    const NUMBERPICTURE = 6;

    public function showEventHomePage()
    {
        return $this->pdo->query('SELECT e.id, image, title, datetime, name , place FROM ' . $this->table . ' e  
         JOIN category c ON c.id = e.category_id
         JOIN representation r ON e.id = r.event_id
         WHERE datetime > CURDATE()
         ORDER BY datetime
         LIMIT ' . self::NUMBERPICTURE . ';')->fetchAll();
    }

    public function showEvent()
    {
        return $this->pdo->query('
        SELECT MONTH(datetime) month, YEAR(datetime) year, e.id, image, title, datetime, place, name 
        FROM ' . $this->table . ' e  
        JOIN category c ON c.id = e.category_id
        JOIN representation r ON e.id = r.event_id
        ORDER BY datetime;')->fetchAll();
    }

    public function showEventPerMonth($month, $year)
    {
        return $this->pdo->query('SELECT e.id, image, title, datetime, place, name FROM ' . $this->table . ' e  
         JOIN category c ON c.id = e.category_id
         JOIN representation r ON e.id = r.event_id
         WHERE MONTH(datetime) = ' . $month . ' AND YEAR(datetime) = ' . $year . '
         ORDER BY datetime;')->fetchAll();
    }

    public function selectEventById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table JOIN category c ON c.id = event.category_id
        JOIN representation r ON event.id = r.event_id WHERE event.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
