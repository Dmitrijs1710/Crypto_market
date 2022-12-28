<?php

namespace App\Repositories;

use App\Models\Collections\UserCoinCollection;
use App\Models\UserCoin;

interface UserCoinRepository
{
    public function insertCoin(UserCoin $userCoin): bool;
    public function getUserCoinByUniqueId(int $coinUniqueId): ?UserCoin;
    public function getCoinCollectionByUserId(int $userId): UserCoinCollection;
    public function updateCoin(string $field, string $value, int $id): bool;
}