<?php

namespace App\Controllers;


use App\Redirect;
use App\Services\BuyService;
use App\Services\CoinFullInfoService;
use App\Services\CoinsGetterService;
use App\Services\SelectedUserCoinGetterService;
use App\Services\SellService;
use App\Template;
use App\Validate;

class CoinController
{
    private BuyService $buyService;
    private CoinsGetterService $coinsGetterService;
    private SelectedUserCoinGetterService $selectedUserCoinGetterService;
    private SellService $sellService;
    private CoinFullInfoService $coinFullInfoService;

    public function __construct(
        BuyService                    $buyService,
        CoinFullInfoService           $coinFullInfoService,
        CoinsGetterService            $coinsGetterService,
        SelectedUserCoinGetterService $selectedUserCoinGetterService,
        SellService                   $sellService
    )
    {
        $this->buyService = $buyService;
        $this->coinsGetterService = $coinsGetterService;
        $this->selectedUserCoinGetterService = $selectedUserCoinGetterService;
        $this->sellService = $sellService;
        $this->coinFullInfoService = $coinFullInfoService;
    }

    public function index(array $vars): Template
    {
        if ($vars['id'] ?? null) {
            $coin = $this->coinFullInfoService->execute($vars['id']);
            if (!empty($_SESSION['id']) && $coin!=null) {
                $userCoin = $this->selectedUserCoinGetterService->execute($_SESSION['id']);
                if ($userCoin->getTotalCountByProductId($vars['id']) > 0) {
                    return new Template('/Coin/coin.twig', [
                        'coin' => $coin,
                        'count' => $userCoin->getTotalCountByProductId($vars['id']),
                        'avg' => $userCoin->getAverageByProductId($vars['id']) / 100,
                        'income' => $coin->getQuote()->getPrice() - ($userCoin->getAverageByProductId($vars['id']) / 100),
                        'buy' => true,
                        'sell' => true,
                        'sellShort' =>true
                    ]);
                }
                return new Template('/Coin/coin.twig', [
                    'coin' => $coin,
                    'buy' => true,
                    'sellShort' =>true
                ]);
            }
            return new Template('/Coin/coin.twig', [
                'coin' => $coin
            ]);
        }
        if ($_GET['search'] ?? null) {
            $coins = $this->coinsGetterService->execute($_GET['search']);
            return new Template('/Coin/index.twig', [
                'coins' => $coins->getAll(),
                'placehold' => $_GET['search']
            ]);
        }
        $coins = $this->coinsGetterService->execute();
        return new Template('/Coin/index.twig', [
            'coins' => $coins->getAll()
        ]);
    }

    public function buy(array $vars): Redirect
    {
        Validate::userCoinInputChecker(floatval($_POST['count']));
        if (empty($_SESSION['error'])) {

            if ($this->buyService->execute(
                intval($_SESSION['id']),
                intval($vars['id']),
                floatval($_POST['count'])
            )) {
                $_SESSION['popup'] = "Coin bought successful";
                return new Redirect('/coin=' . $vars['id']);
            }
        }
        return new Redirect('/coin=' . $vars['id']);
    }

    public function sell(array $vars): Redirect
    {
        Validate::userCoinInputChecker(floatval($_POST['count']), intval($vars['id']));
        if (empty($_SESSION['error'])) {
            if ($this->sellService->execute
            (
                intval($_SESSION['id']),
                intval($vars['id']),
                floatval($_POST['count'])
            )) {
                $_SESSION['popup'] = "Coin sold successful";
                return new Redirect('/coin=' . $vars['id']);
            }
        }
        return new Redirect('/coin=' . $vars['id']);
    }
}