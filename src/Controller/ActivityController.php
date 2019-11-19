<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\EventCarousselManager;
use App\Model\EventManager;

class ActivityController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(int $type)
    {
        //Le type 0 correspond aux cours et 1 aux formations.

        $activityManager = new ActivityManager();

        if ($type === 0 || $type === 1) {
            $activities = $activityManager->selectActivityByType($type);
        }

        switch ($type) {
            case 0:
                $isCourse = true;
                break;
            case 1:
                $isFormation = true;
                break;
        }

        return $this->twig->render('Activity/index.html.twig', [
            'activities' => $activities ?? '',
            'isCourse' => $isCourse ?? '',
            'isFormation' => $isFormation ?? '',
        ]);
    }
}
