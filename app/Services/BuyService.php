<?php

namespace App\Services;

use App\Models\UserCoins\UserCoin;
use App\Models\UserCoins\UserCoinCollection;
use App\Repositories\UserCoinFromMysql;
use App\Repositories\UserFromMysql;

class BuyService
{
    public function execute(int $userId, UserCoin $userCoin, float $newBalance) :bool
    {

        if ((new UserCoinFromMysql())->insertCoin($userCoin)){
            return (new UserFromMysql())->updateUserInformation('balance',strval($newBalance), $userId);
        }
        return false;
    }
}