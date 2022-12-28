<?php

namespace App\Repositories;

use App\Models\Collections\UserCoinCollection;
use App\Models\User;

interface UsersRepository
{
    public function getUserById(int $id): ?User;

    public function insertUser(User $user): ?string;

    public function updateUserInformation(string $field, string $value, string $id): bool;

    public function getUserByEmail(string $email): ?User;

    public function addUserCoins(int $userId, userCoinCollection $userCoins): bool;
}