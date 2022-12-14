<?php

namespace App\Services;

use App\Models\UserCoins\UserCoin;


use App\Repositories\UserCoinRepository;
use App\Repositories\UsersRepository;

class BuyService
{
    private UsersRepository $usersRepository;
    private UserCoinRepository $userCoinRepository;

    public function __construct(UsersRepository $usersRepository, UserCoinRepository $userCoinRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->userCoinRepository = $userCoinRepository;
    }

    public function execute(int $userId, UserCoin $userCoin, float $newBalance) :bool
    {

        if ($this->userCoinRepository->insertCoin($userCoin)){
            return ($this->usersRepository->updateUserInformation('balance',strval($newBalance), $userId));
        }
        return false;
    }
}