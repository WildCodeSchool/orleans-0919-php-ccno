<?php


namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    public function index()
    {
        $eventManager = new EventManager('event');
        $events = $eventManager->showEvent();
        return $this->twig->render('Event/index.html.twig', [
            'events' => $events,
        ]);
    }

    public function detail($id)
    {
        $eventManager = new eventManager('event');
        $detail = $eventManager->selectEventById($id);
        return $this->twig->render('Event/detail.html.twig', [
            'detail' => $detail
        ]);
    }
}
