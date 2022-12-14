<?php

namespace App\ViewVariables;

use App\Services\UserInformationGetterService;

class LoginVariables
{

    public function getName(): string
    {
        return 'login';
    }

    public function getValues(): array
    {
        if (!empty($_SESSION['id'])) {
            $user = (new UserInformationGetterService())->execute($_SESSION['id']);
            return [
                'email' => $user->getEMail(),
                'name' => $user->getName(),
                'balance' =>$user->getBalance()/100
            ];
        }
        return [];
    }
}