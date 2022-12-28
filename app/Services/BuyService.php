<?php

namespace App\Services;

use App\Models\UserCoin;
use App\Models\UserCoinProperties\TransactionData;
use App\Repositories\UserCoinRepository;
use App\Repositories\UsersRepository;
use App\Validate;
use DateTime;


class BuyService
{
    private UsersRepository $usersRepository;
    private UserCoinRepository $userCoinRepository;
    private UserInformationGetterService $userInformationGetterService;
    private CoinFullInfoService $coinFullInfoService;

    public function __construct(
        UsersRepository $usersRepository,
        UserCoinRepository $userCoinRepository,
        UserInformationGetterService $userInformationGetterService,
        CoinFullInfoService $coinFullInfoService
    )
    {
        $this->usersRepository = $usersRepository;
        $this->userCoinRepository = $userCoinRepository;
        $this->userInformationGetterService = $userInformationGetterService;
        $this->coinFullInfoService = $coinFullInfoService;
    }

    public function execute(int $userId, int $userCoinId, float $coinsCount, bool $short = false): bool
    {
        $user = $this->userInformationGetterService->execute($userId);
        $userBalance = $user->getBalance();


        if ($short){
            $userCoin = $this->userCoinRepository->getUserCoinByUniqueId($userCoinId);
            $coin = $this->coinFullInfoService->execute($userCoin->getProductId());
            $coinCost = $coin->getQuote()->getPrice() * 100;
            $coinsCount = $userCoin->getUserCoinTransaction()->getAmount();
            Validate::balanceChecker($userBalance,$coinCost * $coinsCount);
            $userCoin = new UserCoin(
                $userCoinId,
                $userId,
                $coinCost,
                $coin->getLogoUrl(),
                $coin->getName(),
                $coin->getSymbol(),
                new TransactionData('CLOSE-SHORT', new DateTime(), $coinsCount)
            );
            if (empty($_SESSION['error'])){
                if ($this->userCoinRepository->insertCoin($userCoin)) {
                    if ($this->userCoinRepository->updateCoin('open_short', 0, $userCoinId)) {
                        return ($this->usersRepository->updateUserInformation('balance', $userBalance-($coinCost*$coinsCount), $userId));
                    } else {
                        $_SESSION['error']['close'] = 'Update coin database error';
                    }
                } else {
                    $_SESSION['error']['close'] = 'Insert Coin database error';
                }
            }
        } else {
            $coin = $this->coinFullInfoService->execute($userCoinId);
            $coinCost = $coin->getQuote()->getPrice() * 100;
            Validate::balanceChecker($userBalance,$coinCost * $coinsCount);
            $userCoin = new UserCoin(
                $userCoinId,
                $userId,
                $coinCost,
                $coin->getLogoUrl(),
                $coin->getName(),
                $coin->getSymbol(),
                new TransactionData('BUY', new DateTime(), $coinsCount)
            );
        }

        if (empty($_SESSION['error'])){
            if ($this->userCoinRepository->insertCoin($userCoin)) {
                return ($this->usersRepository->updateUserInformation('balance', $userBalance-($coinCost*$coinsCount), $userId));
            } else {
                $_SESSION['error']['buy'] = 'database error';
            }
        }
        return false;
    }
}