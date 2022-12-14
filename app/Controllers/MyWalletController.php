<?php

namespace App\Controllers;

use App\Models\UserCoins\UserCoin;
use App\Redirect;
use App\Services\CoinFullInfoService;
use App\Services\SellService;
use App\Services\UserDataUpdateService;
use App\Services\UserInformationGetterService;
use App\Template;
use App\ViewVariables\UserCoinsVariables;

class MyWalletController
{
    public function index(): Template
    {
        $variable = (new UserCoinsVariables());
        return new Template('/MyWallet/index.html', [
            $variable->getName() => $variable->getValues()
        ]);
    }

    public function success(): Template
    {
        return new Template('/MyWallet/successful.html', []);
    }

    public function deposit(): Redirect
    {
        $depositAmount = $_POST['deposit'];
        $id = $_SESSION['id'];
        $user = (new UserInformationGetterService())->execute($id);
        if (floatval($_POST['deposit']) <= 0) {
            $_SESSION['error']['balance'] = 'Input less than zero';
        }
        if (empty($_SESSION['error'])) {
            (new UserDataUpdateService())->execute(['balance'], [$user->getBalance() + ($depositAmount * 100)], $id);
            return new Redirect('/wallet/successful');
        }
        return new Redirect('/wallet');
    }

    public function sell(array $vars): Redirect
    {
        $user = (new UserInformationGetterService())->execute(intval($_SESSION['id']));
        $coinCost = (new CoinFullInfoService())->execute(intval(($vars['id'])))->getQuote()->getPrice()*100;
        $coinsCount = $user->getUserCoins()->getTotalCountById(intval($vars['id']));
        if ($coinsCount < floatval($_POST['count'])) {
            $_SESSION['error'][$vars['id']] = 'Not enough coins';
        }
        if (floatval($_POST['count'] <= 0)) {
            $_SESSION['error'][$vars['id']] = 'Input less than zero';
        }
        if (empty($_SESSION['error'])) {
            if ((new SellService())->execute
            (
                intval($_SESSION['id']),
                new UserCoin($vars['id'], $_SESSION['id'], 'SELL', $coinCost, floatval($_POST['count'])),
                $user->getBalance() + ($coinCost * $_POST['count']))
            ) {
                return new Redirect('/wallet/successful');
            }
        }
        return new Redirect('/wallet');
    }
}