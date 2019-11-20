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
    public function index(string $type)
    {
        $activityManager = new ActivityManager();

        if ($type === 'course' || $type === 'training') {
            $activities = $activityManager->selectActivityByType($type);
        }

        switch ($type) {
            case 'course':
                $isCourse = true;
                break;
            case 'training':
                $isTraining = true;
                break;
        }

        return $this->twig->render('Activity/index.html.twig', [
            'activities' => $activities ?? '',
            'isCourse' => $isCourse ?? '',
            'isTraining' => $isTraining ?? '',
        ]);
    }
}
