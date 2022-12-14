<?php

namespace App\Services;

use App\Repositories\UserFromMysql;

class AuthenticationService
{
    public function execute(UserLoginRequest $user): bool
    {
        $userFromDatabase = (new UserFromMysql())->getUserByEmail($user->getEmail());
        if ($userFromDatabase != null){
            if ($user->checkRequest($userFromDatabase)) {
                $_SESSION['id'] = $userFromDatabase->getId();
                return true;
            }
        }
        return false;
    }
}