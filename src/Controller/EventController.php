<?php


namespace App\Controller;

class EventController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Event/index.html.twig');
    }
}
