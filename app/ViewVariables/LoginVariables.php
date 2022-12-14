<?php

namespace App\ViewVariables;

use App\Services\UserInformationGetterService;

class LoginVariables
{
    private UserInformationGetterService $userInformationGetterService;

    public function __construct(UserInformationGetterService $userInformationGetterService)
    {
        $this->userInformationGetterService = $userInformationGetterService;
    }

    public function getName(): string
    {
        return 'login';
    }

    public function getValues(): array
    {
        if (!empty($_SESSION['id'])) {
            $user = $this->userInformationGetterService->execute($_SESSION['id']);
            return [
                'email' => $user->getEMail(),
                'name' => $user->getName(),
                'balance' =>$user->getBalance()/100
            ];
        }
        return [];
    }
}