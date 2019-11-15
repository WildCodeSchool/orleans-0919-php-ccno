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
use App\Model\CategoryManager;
use App\Model\EventManager;
use Composer\Script\Event;

/**
 * Class AdminController
 *
 */
class AdminController extends AbstractController
{
    private function cleanInput(array $input): array
    {
        foreach ($input as $key => $value) {
            $data[$key] = trim($value);
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
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAllCategory();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventManager = new EventManager();
            $uploadFile = '';

            if (!empty($_FILES['file'])) {
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
                }
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
                    $eventManager->insertEvent($admin);
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
}
