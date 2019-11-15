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
        $globalEvents = $eventManager->showEvent();
        $currentDateMY = ['month' => date("n"), 'year' => date("Y")];

        $isSetDate = [];
        foreach ($globalEvents as $event) {
            $isSetDate[] = $event['month'] . "/" . $event['year'];
        }

        while (!in_array($currentDateMY['month'] . '/' . $currentDateMY['year'], $isSetDate)) {
            if ($currentDateMY['month'] < 12) {
                $currentDateMY['month'] += 1;
            } elseif ($currentDateMY['month'] === 12) {
                $currentDateMY['month'] = 1;
                $currentDateMY['year'] += 1;
            }
        }
        return $this->twig->render('Home/index.html.twig', [
            'events' => $events,
            'caroussels'=> $caroussels,
            'currentDateMY' => $currentDateMY,
        ]);
    }
}
