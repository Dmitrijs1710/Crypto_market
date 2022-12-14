<?php

namespace App\Controllers;

use App\Models\UserCoins\UserCoin;
use App\Models\UserCoins\UserCoinCollection;
use App\Services\CoinFullInfoService;
use App\Services\SelectedUserCoinGetterService;
use App\Template;

class TransactionsController
{
    public function index(): Template
    {
        $userCoins = (new SelectedUserCoinGetterService())->execute($_SESSION['id']);

        $ids = $userCoins->getUniqueId();

        $userCoinsInfo = [];
        foreach ($ids as $id) {
            $userCoinsInfo[$id] = (new CoinFullInfoService())->execute($id);
        }
        $userCoins = $userCoins->getAll();
        if (!empty($_GET['search'])) {
            foreach ($userCoins as $key => $userCoin) {
                /** @var UserCoin $userCoin */
                if (strpos(strtolower($userCoinsInfo[$userCoin->getId()]->getName()), strtolower($_GET['search'])) === false
                    && strpos(strtolower($userCoinsInfo[$userCoin->getId()]->getSymbol()), strtolower($_GET['search'])) === false) {
                    unset($userCoins[$key]);
                }
            }
        }
        return new Template('/Transactions/index.html',
            [
                'userCoins' => $userCoins,
                'userCoinsInfo' => $userCoinsInfo,
                'placehold' => $_GET['search'] ?? null
            ]
        );
    }
}