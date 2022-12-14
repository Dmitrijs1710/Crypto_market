<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserFromMysql;


class RegistrationService
{
    public function execute(User $user): bool
    {

        if (!empty($_SESSION['error'])) {
            return false;
        }
        $response = (new UserFromMysql())->insertUser($user);
        if ( $response !=null) {
            $_SESSION['id'] = $response;
            return true;

        } else {
            $_SESSION['error']['email'] = "Email already exits";
            return false;
        }

    }
}