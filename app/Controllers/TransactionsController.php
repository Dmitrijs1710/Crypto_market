<?php

namespace App\Controllers;

use App\Models\UserCoins\UserCoin;
use App\Services\SelectedUserCoinGetterService;
use App\Template;

class TransactionsController
{
    private SelectedUserCoinGetterService $selectedUserCoinGetterService;

    public function __construct(SelectedUserCoinGetterService $selectedUserCoinGetterService)
    {
        $this->selectedUserCoinGetterService = $selectedUserCoinGetterService;
    }

    public function index(): Template
    {
        $userCoins = ($this->selectedUserCoinGetterService)->execute($_SESSION['id']);
        $userCoins = $userCoins->getAll();
        if (!empty($_GET['search'])) {
            foreach ($userCoins as $key => $userCoin) {
                /** @var UserCoin $userCoin */
                if (strpos(strtolower($userCoin->getName()), strtolower($_GET['search'])) === false
                    && strpos(strtolower($userCoin->getSymbol()), strtolower($_GET['search'])) === false) {
                    unset($userCoins[$key]);
                }
            }
        }
        return new Template('/Transactions/index.html',
            [
                'userCoins' => $userCoins,
                'placehold' => $_GET['search'] ?? null
            ]
        );
    }
}