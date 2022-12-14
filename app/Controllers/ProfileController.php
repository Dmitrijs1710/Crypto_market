<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserDataUpdateService;
use App\Template;
use App\Validate;

class ProfileController
{
    private UserDataUpdateService $userDataUpdateService;

    public function __construct(UserDataUpdateService $userDataUpdateService)
    {

        $this->userDataUpdateService = $userDataUpdateService;
    }

    public function index(): Template
    {
        $id = $_SESSION['id'] ?? null;
        if ($id === null) {
            header("Location: /login");
            exit();
        }
        return new Template('/Profile/index.html', [
        ]);
    }

    public function updateData(): Redirect
    {
        $field = [];
        $value = [];
        if ($_POST['name'] != null) {
            Validate::nameChecker($_POST['name']);
            $field[] = 'name';
            $value[] = $_POST['name'];
        }
        if ($_POST['password'] != null && $_POST['passwordCurrent'] != null && $_POST['passwordRepeat'] != null) {
            Validate::passwordMatch($_POST['password'], $_POST['passwordRepeat']);
            Validate::passwordChecker($_POST['password']);
            $field[] = 'password';
            $value[] = $_POST['password'];

        } else if ($_POST['password'] != null || $_POST['passwordCurrent'] != null || $_POST['passwordRepeat'] != null) {
            $_SESSION['error']['password'] = "not enough fields provided";
        }
        if ($_POST['email'] != null) {
            Validate::emailChecker($_POST['email']);
            $field[] = 'email';
            $value[] = $_POST['email'];
        }
        if (!empty($field) && empty($_SESSION['error'])) {
            ($this->userDataUpdateService)->execute($field, $value, $_SESSION['id']);
        }

        return new Redirect('/profile');

    }
}