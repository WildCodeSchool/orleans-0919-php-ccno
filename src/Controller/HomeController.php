<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\EventCarousselManager;
use App\Model\EventManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $carousselManager= new EventCarousselManager('event');
        $caroussels = $carousselManager->selectPictureCaroussel();
        $eventManager = new EventManager('event');
        $events = $eventManager->showEventHomePage();
        return $this->twig->render('Home/index.html.twig', [
            'events' => $events,
            'caroussels'=> $caroussels,
            'success' => $_GET['success'] ?? null
        ]);
    }
}
