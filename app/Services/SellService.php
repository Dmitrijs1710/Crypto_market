<?php

namespace App\Services;

use App\Models\UserCoins\UserCoin;

use App\Repositories\UserCoinRepository;

use App\Repositories\UsersRepository;

class SellService
{
    private UserInformationGetterService $userInformationGetterService;
    private UserCoinRepository $userCoinRepository;
    private UsersRepository $usersRepository;

    public function __construct(
        UserInformationGetterService $userInformationGetterService,
        UserCoinRepository           $userCoinRepository,
        UsersRepository              $usersRepository
    )
    {

        $this->userInformationGetterService = $userInformationGetterService;
        $this->userCoinRepository = $userCoinRepository;
        $this->usersRepository = $usersRepository;
    }

    public function execute(int $userId, UserCoin $userCoin, float $newBalance): bool
    {
        $user = $this->userInformationGetterService->execute($userId);
        $userCoins = $user->getUserCoins();
        if ($userCoins->getTotalCountById($userCoin->getId()) >= $userCoin->getCount()) {
            if ($this->userCoinRepository->insertCoin($userCoin)) {
                return $this->usersRepository->updateUserInformation('balance', strval($newBalance), $userId);
            }
        }
        return false;
    }
}