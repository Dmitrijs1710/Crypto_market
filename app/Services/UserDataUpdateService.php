<?php

namespace App\Services;



use App\Repositories\UsersRepository;

class UserDataUpdateService
{
    private UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {

        $this->usersRepository = $usersRepository;
    }

    public function execute(array $field, array $value, int $id): void
    {
        foreach ($field as $key=>$item){
            if ($item === 'password') {
                $value[$key] = password_hash($value[$key], PASSWORD_DEFAULT);
            }
            if ($this->usersRepository->updateUserInformation($item, $value[$key], strval($id))){
                $_SESSION['error'][$item] = 'successfully updated';
            } else {
                $_SESSION['error'][$item] = 'not successfully';
            }

        }
    }
}