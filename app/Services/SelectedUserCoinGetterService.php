<?php

namespace App\Services;

use App\Repositories\UserCoinFromMysql;

class SelectedUserCoinGetterService
{
    public function execute(int $userId){
        return (new UserCoinFromMysql())->getCoinCollectionByUserId($userId);
    }
}