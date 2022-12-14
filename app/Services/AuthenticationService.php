<?php

namespace App\Services;

use App\Repositories\UserFromMysqlRepository;
use App\Repositories\UsersRepository;

class AuthenticationService
{

    private UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {


        $this->usersRepository = $usersRepository;
    }

    public function execute(UserLoginRequest $user): bool
    {
        $userFromDatabase = $this->usersRepository->getUserByEmail($user->getEmail());
        if ($userFromDatabase != null){
            if ($user->checkRequest($userFromDatabase)) {
                $_SESSION['id'] = $userFromDatabase->getId();
                return true;
            }
        }
        return false;
    }
}