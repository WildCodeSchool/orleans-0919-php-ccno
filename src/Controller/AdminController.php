<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\AdminManager;

/**
 * Class AdminController
 *
 */
class AdminController extends AbstractController
{


    /**
     * Display admin listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $adminManager = new AdminManager();
        $events = $adminManager->selectAllEvents();
        return $this->twig->render('Admin/index.html.twig', ['events' => $events]);
    }


    /**
     * Display admin informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $adminManager = new AdminManager();
        $event = $adminManager->selectEventById($id);
        return $this->twig->render('Admin/show.html.twig', ['event' => $event]);
    }
}
