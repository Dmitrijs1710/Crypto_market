<?php

namespace App\Controllers;

use App\Models\UserCoins\UserCoin;
use App\Redirect;
use App\Services\CoinFullInfoService;
use App\Services\SellService;
use App\Services\UserCoinsVariablesService;
use App\Services\UserDataUpdateService;
use App\Services\UserInformationGetterService;
use App\Template;

class MyWalletController
{
    private CoinFullInfoService $coinFullInfoService;
    private SellService $sellService;
    private UserInformationGetterService $userInformationGetterService;
    private UserDataUpdateService $userDataUpdateService;
    private UserCoinsVariablesService $coinsVariablesService;

    public function __construct(
        CoinFullInfoService $coinFullInfoService,
        SellService $sellService,
        UserInformationGetterService $userInformationGetterService,
        UserDataUpdateService $userDataUpdateService,
        UserCoinsVariablesService $coinsVariablesService
    )
    {
        $this->coinFullInfoService = $coinFullInfoService;
        $this->sellService = $sellService;
        $this->userInformationGetterService = $userInformationGetterService;
        $this->userDataUpdateService = $userDataUpdateService;
        $this->coinsVariablesService = $coinsVariablesService;
    }

    public function index(): Template
    {
        $variable = $this->coinsVariablesService;
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
        $user = $this->userInformationGetterService->execute($id);
        if (floatval($_POST['deposit']) <= 0) {
            $_SESSION['error']['balance'] = 'Input less than zero';
        }
        if (empty($_SESSION['error'])) {
            $this->userDataUpdateService->execute(['balance'], [$user->getBalance() + ($depositAmount * 100)], $id);
            return new Redirect('/wallet/successful');
        }
        return new Redirect('/wallet');
    }

    public function sell(array $vars): Redirect
    {
        $user = $this->userInformationGetterService->execute(intval($_SESSION['id']));
        $coin =$this->coinFullInfoService->execute(intval(($vars['id'])));
        $coinCost =$coin->getQuote()->getPrice()*100;
        $coinsCount = $user->getUserCoins()->getTotalCountById(intval($vars['id']));
        if ($coinsCount < floatval($_POST['count'])) {
            $_SESSION['error'][$vars['id']] = 'Not enough coins';
        }
        if (floatval($_POST['count'] <= 0)) {
            $_SESSION['error'][$vars['id']] = 'Input less than zero';
        }
        if (empty($_SESSION['error'])) {
            if ($this->sellService->execute
            (
                intval($_SESSION['id']),
                new UserCoin(
                    $vars['id'],
                    $_SESSION['id'],
                    'SELL',
                    $coinCost,
                    floatval($_POST['count']),
                    $coin->getLogoUrl(),
                    $coin->getName(),
                    $coin->getSymbol()
                ),
                $user->getBalance() + ($coinCost * $_POST['count']))
            ) {
                return new Redirect('/wallet/successful');
            }
        }
        return new Redirect('/wallet');
    }
}