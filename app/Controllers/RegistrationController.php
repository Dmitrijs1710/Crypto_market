<?php

namespace App\Controllers;

use App\Models\User;
use App\Redirect;
use App\Services\RegistrationService;
use App\Template;
use App\Validate;


class RegistrationController
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {

        $this->registrationService = $registrationService;
    }

    public function index(): Template
    {
        if ($_SESSION['id'] !== null) {
            header("Location: /");
            exit();
        }
        return new Template('Registration/registration.twig', [
        ]);
    }

    public function registrationHandler(): Redirect
    {
        Validate::passwordMatch($_POST['password'], $_POST['passwordRepeat']);
        Validate::passwordChecker($_POST['password']);
        Validate::emailChecker($_POST['email']);
        Validate::nameChecker($_POST['name']);
        $user = new User($_POST['email'], $_POST['name'], 0, null, $_POST['password']);
        if ($this->registrationService->execute($user)) {
            $_SESSION['popup'] = "Coin bought successful";
            return new Redirect('/profile');
        } else {
            return new Redirect('/registration');
        }
    }
}