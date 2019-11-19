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
use App\Model\RepresentationManager;
use DateTime;

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

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new AdminManager();
            $adminManager->delete($id);

            header('Location:/admin/index');
        }
    }

    private function validationRepresentation(array $data): array
    {
        $errors = [];
        $dataClean = $this->cleanInput($data);
        if (empty($dataClean['price'])) {
            $errors['price'] = 'Le prix est manquant';
        } elseif ($dataClean['price'] < 0) {
            $errors['price'] = 'Le prix est inférieur à 0';
        } elseif (is_float($dataClean['price'])) {
            $errors['price'] = 'Le prix est n\'est pas un nombre à virgule';
        }
        if (empty($dataClean['place'])) {
            $errors['place'] = 'Le lieu est manquant';
        } elseif (strlen($dataClean['place']) > 100) {
            $errors['place'] = 'Le nom de lieu est trop long (plus de 100 caractères)';
        }
        if (empty($dataClean['datetime'])) {
            $errors['datetime'] = 'La date et l\'heure sont manquantes';
        }
        if (empty($dataClean['duration']) > 100) {
            $errors['duration'] = 'La durée est manquante';
        } elseif (strlen($dataClean['duration'])) {
            $errors['duration'] = 'La durée est trop longue (plus de 100 caractères)';
        }
        return $errors ?? [];
    }

    public function addRepresentation()
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectAllEvents();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $represManager = new RepresentationManager();
            $admin = [
                'price' => $_POST['price'],
                'event_id' => $_POST['choosenEvent'],
                'place' => $_POST['location'],
                'datetime' => $_POST['date'],
                'duration' => $_POST['duration'],
            ];
            $errors = $this->validationRepresentation($admin);
            if (empty($errors)) {
                $date = new DateTime($admin['datetime']);
                $admin['datetime'] = $date->format('Y-m-d H:i:s');
                $represManager->addRepresentation($admin);
                header('Location: /admin/index');
            } else {
                return $this->twig->render('Admin/addRepresentation.twig', [
                    "events" => $events,
                    'errors' => $errors]);
            }
        }
        return $this->twig->render('Admin/addRepresentation.twig', ["events" => $events]);
    }
}
