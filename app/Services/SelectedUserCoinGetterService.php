<?php

namespace App\Services;

use App\Models\UserCoins\UserCoinCollection;

use App\Repositories\UserCoinRepository;

class SelectedUserCoinGetterService
{
    private UserCoinRepository $userCoinRepository;

    public function __construct(UserCoinRepository $userCoinRepository)
    {

        $this->userCoinRepository = $userCoinRepository;
    }

    public function execute(int $userId): UserCoinCollection
    {
        return $this->userCoinRepository->getCoinCollectionByUserId($userId);
    }
}