<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\BuyService;
use App\Services\SellService;
use App\Services\SendService;
use App\Services\UserCoinsVariablesService;
use App\Services\UserDataUpdateService;
use App\Services\UserInformationGetterService;
use App\Template;
use App\Validate;

class MyWalletController
{
    private SellService $sellService;
    private UserInformationGetterService $userInformationGetterService;
    private UserDataUpdateService $userDataUpdateService;
    private UserCoinsVariablesService $coinsVariablesService;
    private SendService $sendService;
    private BuyService $buyService;

    public function __construct(
        SellService                  $sellService,
        UserInformationGetterService $userInformationGetterService,
        UserDataUpdateService        $userDataUpdateService,
        UserCoinsVariablesService    $coinsVariablesService,
        SendService $sendService,
        BuyService $buyService
    )
    {
        $this->sellService = $sellService;
        $this->userInformationGetterService = $userInformationGetterService;
        $this->userDataUpdateService = $userDataUpdateService;
        $this->coinsVariablesService = $coinsVariablesService;
        $this->sendService = $sendService;
        $this->buyService = $buyService;
    }

    public function index(array $vars): Template
    {
        $coinId = $vars['id'] ?? null;
        $coinVariables = $this->coinsVariablesService->execute($_SESSION['id']?? null, $coinId);
        return new Template('/MyWallet/index.twig', [
            'userCoins' => $coinVariables,
            'sendForm' => $vars['id'] !==null,
            'sellForm' => $vars['id'] !==null,
        ]);
    }

    public function success(): Template
    {
        return new Template('/MyWallet/successful.twig', []);
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
            $_SESSION['popup'] = "Deposit successful";
            return new Redirect('/wallet');
        }
        return new Redirect('/wallet');
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
                return new Redirect('/wallet');
            }
        }
        return new Redirect('/wallet');
    }
    public function send(array $vars) :Redirect
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $amount = $_POST['amount'];
        $coinId = $vars['id'];
        $userId = $_SESSION['id'];
        Validate::userCoinInputChecker($amount, intval($coinId));
        Validate::emailChecker($email);
        Validate::passwordChecker($password);
        if (empty($_SESSION['error'])){
            if ($this->sendService->execute($userId, $email,$password, $coinId,$amount)){
                $_SESSION['popup'] = "Coin sent successful";
                return new Redirect('/wallet');
            }
        }
        return new Redirect('/wallet/coin=' . $coinId);
    }
    public function sellShort(array $vars) :Redirect
    {
        Validate::userCoinInputChecker(floatval($_POST['count']), intval($vars['id']));
        if (empty($_SESSION['error'])) {
            if ($this->sellService->execute
            (
                intval($_SESSION['id']),
                intval($vars['id']),
                floatval($_POST['count']),
                true
            )) {
                $_SESSION['popup'] = "Sell short successful";
                return new Redirect('/wallet');
            }
        }
        return new Redirect('/coin=' . $vars['id']);
    }
    public function closeShort(array $vars) :Redirect
    {
        if ($this->buyService->execute
        (
            intval($_SESSION['id']),
            intval($vars['id']),
            0,
            true
        )) {
            $_SESSION['popup'] = "Close short successful";
            return new Redirect('/wallet');
        }
        return new Redirect('/wallet');
    }
}