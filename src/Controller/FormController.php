<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

/**
 * Class FormController
 *
 */
class FormController extends AbstractController
{

    private function cleanInput(array $input): array
    {
        foreach ($input as $key => $value) {
            $input[$key] = trim($value);
        }
        return $input;
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Le nom est requis';
        } elseif (strlen($data['name']) > 40) {
            $errors['name'] = 'Le nom est trop long';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Un email valide est requis';
        } elseif (strlen($data['email']) > 50) {
            $errors['email'] = 'L\'email est trop long';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "votre email est invalide";
        }
        if (empty($data['message'])) {
            $errors['message'] = 'Le message est requis';
        }
        return $errors ?? [];
    }

    public function index()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->cleanInput($_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                $transport = Transport::fromDsn(MAIL_DSN);
                $mailer = new Mailer($transport);

                $email = (new Email())
                    ->from(MAIL_FROM)
                    ->to(MAIL_TO)
                    ->subject('Formulaire de contact du CCNO')
                    ->html($this->twig->render('Contact/email.html.twig', [
                        'data'  => $data,
                    ]));

                $mailer->send($email);
                header('Location: /Home/index/?success=ok');
            }
        }
        return $this->twig->render('Contact/form.html.twig', [
            'data'  => $data ?? [],
            'errors' => $errors,
        ]);
    }
}
