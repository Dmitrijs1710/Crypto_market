<?php

namespace App\Services;

use App\Models\User;

use App\Repositories\UsersRepository;


class RegistrationService
{
    private UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function execute(User $user): bool
    {

        if (!empty($_SESSION['error'])) {
            return false;
        }
        $response = $this->usersRepository->insertUser($user);
        if ( $response !=null) {
            $_SESSION['id'] = $response;
            return true;

        } else {
            $_SESSION['error']['email'] = "Email already exits";
            return false;
        }

    }
}