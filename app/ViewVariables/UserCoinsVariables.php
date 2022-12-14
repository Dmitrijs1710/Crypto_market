<?php

namespace App\ViewVariables;

use App\Services\CoinFullInfoService;
use App\Services\UserInformationGetterService;

class UserCoinsVariables
{
    public function getName(): string
    {
        return 'userCoins';
    }

    public function getValues(): array
    {
        if (!empty($_SESSION['id'])) {
            $user = (new UserInformationGetterService())->execute($_SESSION['id']);
            $userCoins = [];
            if ($user->getUserCoins()!=null) {
                foreach ($user->getUserCoins()->getUniqueId() as $id){
                    $userCoinCount=$user->getUserCoins()->getTotalCountById($id);
                    $coin = (new CoinFullInfoService())->execute($id);
                    $avg = $user->getUserCoins()->getAverageById($id)/100;
                    if($userCoinCount>0){
                        $userCoins[] = [
                            'count' =>$userCoinCount,
                            'coin' => $coin,
                            'avg' => $avg,
                            'income' => $coin->getQuote()->getPrice()-$avg
                        ];
                    }
                }
            }
            return [
                'name' => $user->getName(),
                'email' => $user->getEMail(),
                'coins' => $userCoins,
                'userBalance' => $user->getBalance()/100,
            ];
        }
        return [];
    }
}