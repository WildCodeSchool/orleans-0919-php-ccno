<?php


namespace App\Controller;

use App\Model\AdminManager;

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
        $admins = $adminManager->selectAll();

        return $this->twig->render('Admin/index.html.twig', ['admins' => $admins]);
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
            $admin = [
                'title' => $_POST['title'],
            ];
            $id = $adminManager->insert($admin);
            header('Location:/admin/show/' . $id);
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