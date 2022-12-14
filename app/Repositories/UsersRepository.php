<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserCoins\UserCoin;
use App\Models\UserCoins\UserCoinCollection;

interface UsersRepository
{
    public function getUserById(int $id): ?User;

    public function insertUser(User $user): ?string;

    public function updateUserInformation(string $field, string $value, string $id): bool;

    public function getUserByEmail(string $email): ?User;

    public function addUserCoins(int $userId, userCoinCollection $userCoins): bool;
}