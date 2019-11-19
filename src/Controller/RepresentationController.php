<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\EventManager;
use App\Model\RepresentationManager;

class RepresentationController extends AbstractController
{

    /**
     * Display presention page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index($eventId)
    {
        $represManager = new RepresentationManager();
        $representations = $represManager->showOne($eventId);
        $eventManager = new EventManager();
        $event = $eventManager->selectOneById($eventId);

        return $this->twig->render('AdminRepresentation/index.html.twig', [
            'representations' => $representations,
            'event' => $event,
        ]);
    }
}
