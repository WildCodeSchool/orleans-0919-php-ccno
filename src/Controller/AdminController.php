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

    public function edit($id): string
    {
        $errors = [];
        $eventManager = new AdminManager();
        $event = $eventManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $data['image'] = $event['image'];
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

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new AdminManager();
            $adminManager->delete($id);

            header('Location:/admin/index');
        }
    }
}
