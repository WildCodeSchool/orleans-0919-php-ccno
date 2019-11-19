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
    public function course()
    {
        $activities = [
        ['public' => 'Pour les enfants',
        'image' => '../assets/images/dancing_kids.jpg',
        'title' => 'Cours de danse',
        'recurrence' => 'Tous les mercredis de Septembre 2019 à juin 2020',
        'price' => 'séance d\'essai 10€',
        'link' => 'https://ccn-orleans-reservations.mapado.com/event/orleans/dancing-kids-avec-anne-perbal-inscription',
            ],
        ['public' => 'Pour les adultes',
            'image' => '../assets/images/kizumba.jpg',
            'title' => 'Cours de danse',
            'recurrence' => 'Du 21 septembre 2019 au 25 janvier 2020',
            'price' => 'séance d\'essai gratuite',
        'link' => 'https://ccn-orleans-reservations.mapado.com/event/orleans/dancing-kids-avec-anne-perbal-inscription',
            ],
        ['public' => 'Pour Tous',
            'image' => '../assets/images/@kiki-papadopoulou.jpg',
            'title' => 'Brunch',
            'recurrence' => 'Vendredi 8 novembre, 19h, au CCNO',
            'price' => '10€ / 8€, sur réservation',
        'link'=>'https://ccn-orleans-reservations.mapado.com/event/orleans/dancing-kids-avec-anne-perbal-inscription',
            ],
        ];

        return $this->twig->render('Activity/course.html.twig', [
            'activities' => $activities,
        ]);
    }

    public function formation()
    {
        $activities = [
        ['public' => 'Pour les professionnels',
            'image' => '../assets/images/stage.jpg',
            'title' => 'Stage',
            'recurrence' => 'du lundi 4 au vendredi 8 novembre 2019',
            'price' => 'Au CCNO',
            'link' => 'https://ccn-orleans-reservations.mapado.com/',
        ],
        ['public' => 'Pour les jeunes',
            'image' => '../assets/images/stage_jeunes.jpg',
            'title' => 'immertion danses afro americaines',
        'recurrence' => 'Du 18 octobre au 25 actobre 2020',
        'price' => '50€ le weekend',
        'link'=>'https://ccn-orleans-reservations.mapado.com/event/orleans/dancing-kids-avec-anne-perbal-inscription',
            ],
        ];

        return $this->twig->render('Activity/formation.html.twig', [
            'activities' => $activities,
        ]);
    }
}
