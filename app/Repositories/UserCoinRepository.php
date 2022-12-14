<?php

namespace App\Repositories;

use App\Models\UserCoins\UserCoin;
use App\Models\UserCoins\UserCoinCollection;

interface UserCoinRepository
{
    public function insertCoin(UserCoin $userCoin): bool;

    public function getCoinCollectionByUserId(int $userId): UserCoinCollection;
}