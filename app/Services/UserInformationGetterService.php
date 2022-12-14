<?php

namespace App\Services;

use App\Models\User;

use App\Repositories\UserCoinRepository;

use App\Repositories\UsersRepository;

class UserInformationGetterService
{
    private UserCoinRepository $userCoinRepository;
    private UsersRepository $usersRepository;

    public function __construct(UserCoinRepository $userCoinRepository, UsersRepository $usersRepository)
    {
        $this->userCoinRepository = $userCoinRepository;
        $this->usersRepository = $usersRepository;
    }

    public function execute(int $id): ?User
    {
        $user = $this->usersRepository->getUserById($id);
        $userCoins = $this->userCoinRepository->getCoinCollectionByUserId($id);
        $user->setUserCoins($userCoins);
        return ($user);
    }
}