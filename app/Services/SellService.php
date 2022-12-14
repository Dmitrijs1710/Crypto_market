<?php

namespace App\Services;

use App\Models\UserCoins\UserCoin;
use App\Repositories\UserCoinFromMysql;
use App\Repositories\UserFromMysql;

class SellService
{
    public function execute(int $userId, UserCoin $userCoin, float $newBalance) :bool
    {
        $user = (new UserInformationGetterService())->execute($userId);
        $userCoins = $user->getUserCoins();
        if($userCoins->getTotalCountById($userCoin->getId())>=$userCoin->getCount()){
            if ((new UserCoinFromMysql())->insertCoin($userCoin)){
                return (new UserFromMysql())->updateUserInformation('balance',strval($newBalance), $userId);
            }
        }
        return false;
    }
}