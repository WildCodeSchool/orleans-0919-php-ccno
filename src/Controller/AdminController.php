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
use App\Model\EventManager;
use App\Model\CategoryManager;

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

    public function edit($id): string
    {
        $errors = [];
        $eventManager = new AdminManager();
        $event = $eventManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            if (empty($_FILES['image'])) {
                $data['image'] = $event['image'];
            } else {
                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
                    $data['image'] = "/$uploadFile";
                }
            }
            if (!empty($data['ccno'])) {
                $data['ccno'] = 1;
            } else {
                $data['ccno'] = 0;
            }
            if (!empty($data['caroussel'])) {
                $data['caroussel'] = 1;
            } else {
                $data['caroussel'] = 0;
            }
            $errors = $this->validation($data);
            if (empty($errors)) {
                $eventManager->updateEvent($data);
                header('Location: /admin/index/');
            }
        }

        return $this->twig->render('Admin/edit.html.twig', [
            'event' => $event,
            'data' => $data ?? [],
            'errors' => $errors,
        ]);
    }

    private function validation(array $data): array
    {
        $errors = [];
        if (empty($data['title'])) {
            $errors['title'] = 'Le titre est manquant';
        } elseif (strlen($data['title']) > 45) {
            $errors['title'] = 'Le titre trop long';
        }
        if (empty($data['description'])) {
            $errors['description'] = 'La description est manquante';
        }
        return $errors ?? [];
    }

    private function cleanInput(array $input): array
    {
        foreach ($input as $key => $value) {
            $input[$key] = trim($value);
        }

        return $input;
    }

    private function cleanFormular(array $input): array
    {
        $errors=[];
        $inputFormular = $this->cleanInput($input);
        $errors = $this->validation($inputFormular);
        return $errors;
    }

    public function add()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAllCategory();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventManager = new EventManager();
            $uploadFile = '';
            if (isset($_POST['categorySubmit'])) {
                $category = [
                    'nameCategory' => $_POST['category'],
                ];
                $categoryManager->insertCategory($category);
                header('Location: /admin/add');
                return $this->twig->render('Admin/add.html.twig', ['categories' => $categories]);
            }
            if (!empty($_FILES['image'])) {
                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
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
                    var_dump($errors);
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
        }
        return $this->twig->render('Admin/add.html.twig', ['categories' => $categories]);
    }

    public function addRepresentation()
    {
        return $this->twig->render('Admin/addRepresentation');
    }
}
