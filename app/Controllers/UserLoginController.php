<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\AuthenticationService;
use App\Services\UserLoginRequest;
use App\Template;

class UserLoginController
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {

        $this->authenticationService = $authenticationService;
    }

    public function index(): Template
    {
        if ($_SESSION['id'] !== null) {
            header("Location: /");
            exit();
        }
        return new Template('Login/login.twig', [
        ]);
    }

    public function loginHandler(): Redirect
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = ($this->authenticationService)->execute(new UserLoginRequest($email, $password));
        if ($response) {
            $_SESSION['popup'] = "Login successful";
            return new Redirect('/profile');
        }
        $_SESSION['error']['message'] = "Incorrect email or password";
        return new Redirect('/login');

    }

    public function logoutHandler(): Redirect
    {
        unset($_SESSION['id']);
        return new Redirect('/login');
    }
}