<?php

namespace App\Services;

class FormValidator
{
    public function checkSignIn($user): bool
    {
        $userManager = new UserManager();
        $session = new MessageFlash();

        if (empty($_POST["password"]) || empty($_POST["pseudo"])) {
            $session->setFlashMessage(FORM_FIELDS, 'warning');
            return false;
        } elseif (!$userManager->checkIfPseudoExist($user)) {
            $session->setFlashMessage('Le pseudo n\'éxiste pas !', 'danger');
            return false;
        } elseif (!$userManager->checkPasswordHash($user)) {
            $session->setFlashMessage('Mauvais mot de passe !', 'danger');
            return false;
        }
        return true;
    }

    public function checkSignUp($user): bool
    {
        $session = new MessageFlash();
        $userManager = new UserManager();
        if (!$userManager->isNotEmpty($user)) {
            $session->setFlashMessage(FORM_FIELDS, 'warning');
            return false;
        } elseif (!$userManager->checkPasswordLength()) {
            $session->setFlashMessage('Le mot de passe est trop court !', 'danger');
            return false;
        } elseif (!$userManager->checkPseudo($user)) {
            $session->setFlashMessage('Le pseudo est déjà utilisé !', 'danger');
            return false;
        } elseif (!$userManager->checkEmail($user)) {
            $session->setFlashMessage('Le mail est déjà utilisé !', 'danger');
            return false;
        }
        return true;
    }

    public function checkPost($post): bool
    {

        $session = new MessageFlash();
        $postManager = new PostsManager();
        if (!$postManager->isNotEmpty($post)) {
            $session->setFlashMessage(FORM_FIELDS, 'warning');
            return false;
        }
        if (!$postManager->checkLength(50, $_POST['title'])) {
            $session->setFlashMessage('Le titre est trop long (max. 50) !', 'danger');
            return false;
        }
        if (!$postManager->checkLength(100, $_POST['lead'])) {
            $session->setFlashMessage('Le châpo est trop long (max. 100) !', 'danger');
            return false;
        }
        return true;
    }

    public function checkAnswer($answer): bool
    {

        $session = new MessageFlash();
        if (empty($answer)) {
            $session->setFlashMessage(FORM_FIELDS, 'warning');
            return false;
        }
        return true;
    }

    public function checkModifProfile($blogger): bool
    {
        $session = new MessageFlash();
        if (empty($blogger["description"]) || empty($blogger["country"]) || empty($blogger["image"])) {
            $session->setFlashMessage(FORM_FIELDS, 'warning');
            return false;
        }
        return true;
    }
}
