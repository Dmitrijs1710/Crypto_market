<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserCoinFromMysql;
use App\Repositories\UserFromMysql;

class UserInformationGetterService
{
    public function execute(int $id): ?User
    {
        $user =(new UserFromMysql())->getUserById($id);
        $userCoins = (new UserCoinFromMysql())->getCoinCollectionByUserId($id);
        $user->setUserCoins($userCoins);
        return ($user);
    }
}