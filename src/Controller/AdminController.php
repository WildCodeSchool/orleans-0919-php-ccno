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

    private function cleanInput(array $input): array
    {
        foreach ($input as $key => $value) {
            $data[$key] = trim($value);
            $data[$key] = stripslashes($data[$key]);
            $data[$key] = htmlspecialchars($data[$key]);
            $data[$key] = htmlentities($data[$key]);
        }
        return $input;
    }

    private function cleanFormular(array $input): array
    {
        $errors=[];

        $this->cleanInput($input);

        if (empty($input['title'])) {
            $errors[0] = 'Empty Title';
        }

        if (empty($input['image'])) {
            $errors[1] = 'Empty Image';
        }

        if (empty($input['description'])) {
            $errors[2] = 'Empty Description';
        }

        if (empty($input['category'])) {
            $errors[3] = 'Empty Category';
        }

        return $errors;
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
            $uploadFile = '';

            if (!empty($_FILES['file'])) {
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
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
                $errors = $this->cleanFormular($admin);
                if (empty($errors)) {
                    $adminManager->insertEvent($admin);
                    header('Location: /admin/index');
                } else {
                    return $this->twig->render('Admin/add.html.twig', [
                        'categories' => $categories,
                        'errors' => $errors]);
                }
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
