<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\AuthenticationService;
use App\Services\UserLoginRequest;
use App\Template;

class UserLoginController
{
    public function index(): Template
    {
        if ($_SESSION['id'] !== null) {
            header("Location: /");
            exit();
        }
        return new Template('Login/login.html', [
        ]);
    }

    public function loginHandler(): Redirect
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = (new AuthenticationService())->execute(new UserLoginRequest($email, $password));
        if ($response) {
            return new Redirect('/login/successful');
        }
        $_SESSION['error']['message'] = "Incorrect email or password";
        return new Redirect('/login');

    }

    public function logoutHandler(): Redirect
    {
        unset($_SESSION['id']);
        return new Redirect('/login');
    }

    public function successful(): Template
    {
        return new Template('/Login/successful.html', [
        ]);

    }
}