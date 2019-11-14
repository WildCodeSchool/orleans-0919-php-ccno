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
        $admin = $adminManager->selectOneById($id);

        return $this->twig->render('Admin/show.html.twig', ['admin' => $admin]);
    }


    /**
     * Display admin edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $adminManager = new AdminManager();
        $admin = $adminManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin['title'] = $_POST['title'];
            $adminManager->update($admin);
        }

        return $this->twig->render('Admin/edit.html.twig', ['admin' => $admin]);
    }


    /**
     * Display admin creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $adminManager = new AdminManager();
        $categories = $adminManager->selectAllCategory();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new AdminManager();

            if (!empty($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    var_dump('Erreur n°'.$_FILES['file']['error']);
                }
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . $_FILES['file']['name'];
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                        echo 'Fichier enregistré';
                        var_dump($_FILES['file']['name']);
                    } else {
                        var_dump('Erreur lors de l\'enregistrement');
                    }
                } else {
                    var_dump('Fichier non uploadé');
                }
            }

            if (isset($_POST['categorySubmit'])) {
                $category = [
                    'nameCategory' => $_POST['category'],
                ];
                $adminManager->insertCategory($category);
                header('Location: /admin/add');
            }

            if (isset($_POST['event'])) {
                if (!empty($_POST['ccno'])) {
                    $ccno = 1;
                } else {
                    $ccno = 0;
                }
                if (!empty($_POST['caroussel'])) {
                    $caroussel = 1;
                } else {
                    $caroussel = 0;
                }
                $admin = [
                    'title' => $_POST['title'],
                    'category' => $_POST['choosenCategory'],
                    'ccno' => $ccno,
                    'caroussel' => $caroussel,
                    'description' => $_POST['description'],
                    'image' => $uploadFile,
                ];
                var_dump($admin);
                var_dump($_POST);
                $adminManager->insertEvent($admin);
            }
        }
        return $this->twig->render('Admin/add.html.twig', ['categories' => $categories]);
    }


    /**
     * Handle admin deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $adminManager = new AdminManager();
        $adminManager->delete($id);
        header('Location:/admin/index');
    }
}
