<?php

namespace App\Controllers;

use App\Models\UserCoins\UserCoin;
use App\Redirect;
use App\Services\BuyService;
use App\Services\CoinFullInfoService;
use App\Services\CoinsGetterService;
use App\Services\SelectedUserCoinGetterService;
use App\Services\SellService;
use App\Services\UserInformationGetterService;
use App\Template;

class CoinController
{
    private BuyService $buyService;
    private CoinFullInfoService $coinFullInfoService;
    private CoinsGetterService $coinsGetterService;
    private SelectedUserCoinGetterService $selectedUserCoinGetterService;
    private SellService $sellService;
    private UserInformationGetterService $userInformationGetterService;

    public function __construct(
        BuyService                    $buyService,
        CoinFullInfoService           $coinFullInfoService,
        CoinsGetterService            $coinsGetterService,
        SelectedUserCoinGetterService $selectedUserCoinGetterService,
        SellService                   $sellService,
        UserInformationGetterService  $userInformationGetterService
    )
    {
        $this->buyService = $buyService;
        $this->coinFullInfoService = $coinFullInfoService;
        $this->coinsGetterService = $coinsGetterService;
        $this->selectedUserCoinGetterService = $selectedUserCoinGetterService;
        $this->sellService = $sellService;
        $this->userInformationGetterService = $userInformationGetterService;
    }

    public function index(array $vars): Template
    {
        if ($vars['id'] ?? null) {
            $coin = (new CoinFullInfoService)->execute($vars['id']);
            if (!empty($_SESSION['id'])) {
                $userCoin = $this->selectedUserCoinGetterService->execute($_SESSION['id']);
                if ($userCoin->getTotalCountById($vars['id']) > 0) {
                    return new Template('/Coin/coin.html', [
                        'coin' => $coin,
                        'count' => $userCoin->getTotalCountById($vars['id']),
                        'avg' => $userCoin->getAverageById($vars['id']) / 100,
                        'income' => $coin->getQuote()->getPrice() - ($userCoin->getAverageById($vars['id']) / 100),
                        'buy' => true,
                        'sell' => true,
                    ]);
                }
                return new Template('/Coin/coin.html', [
                    'coin' => $coin,
                    'buy' => true
                ]);
            }
            return new Template('/Coin/coin.html', [
                'coin' => $coin
            ]);
        }
        if ($_GET['search'] ?? null) {
            $coins = $this->coinsGetterService->execute($_GET['search']);
            return new Template('/Coin/index.html', [
                'coins' => $coins->getAll(),
                'placehold' => $_GET['search']
            ]);
        }
        $coins = $this->coinsGetterService->execute();
        return new Template('/Coin/index.html', [
            'coins' => $coins->getAll()
        ]);
    }

    public function buy(array $vars): Redirect
    {
        $userBalance = $this->userInformationGetterService->execute($_SESSION['id'])->getBalance();
        $coin = $this->coinFullInfoService->execute(intval(($vars['id'])));
        $coinCost = $coin->getQuote()->getPrice() * 100;
        $coinsCount = $_POST['count'];
        if ($userBalance < $coinCost * $coinsCount) {
            $_SESSION['error']['buy'] = 'Not enough balance';
        }
        $userCoin = new UserCoin(
            intval($vars['id']),
            $_SESSION['id'],
            'BUY',
            $coinCost,
            $_POST['count'],
            $coin->getLogoUrl(),
            $coin->getName(),
            $coin->getSymbol()
        );
        if (floatval($_POST['count'] <= 0)) {
            $_SESSION['error']['sell'] = 'Input less than zero';
        }

        if (empty($_SESSION['error'])) {
            if ($this->buyService->execute(intval($_SESSION['id']), $userCoin, $userBalance - ($coinCost * $coinsCount))) {
                return new Redirect('/wallet/successful');
            } else {

                $_SESSION['error']['balance'] = 'database error';
            }
        }
        return new Redirect('/coin=' . $vars['id']);
    }

    public function sell(array $vars): Redirect
    {
        $user = $this->userInformationGetterService->execute(intval($_SESSION['id']));
        $coin = $this->coinFullInfoService->execute(intval(($vars['id'])));
        $coinCost = $coin->getQuote()->getPrice() * 100;
        $coinsCount = $user->getUserCoins()->getTotalCountById(intval($vars['id']));
        if ($coinsCount < floatval($_POST['count'])) {
            $_SESSION['error']['sell'] = 'Not enough coins';
        }
        if (floatval($_POST['count'] <= 0)) {
            $_SESSION['error']['sell'] = 'Input less than zero';
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
        return new Redirect('/coin=' . $vars['id']);
    }
}