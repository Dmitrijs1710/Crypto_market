<?php

namespace App\Services;

use App\Models\UserCoin;
use App\Models\UserCoinProperties\TransactionData;
use App\Repositories\UserCoinRepository;
use App\Repositories\UsersRepository;
use App\Validate;

class SellService
{
    private UserInformationGetterService $userInformationGetterService;
    private UserCoinRepository $userCoinRepository;
    private UsersRepository $usersRepository;
    private CoinFullInfoService $coinFullInfoService;

    public function __construct(
        UserInformationGetterService $userInformationGetterService,
        UserCoinRepository           $userCoinRepository,
        UsersRepository              $usersRepository,
        CoinFullInfoService          $coinFullInfoService
    )
    {

        $this->userInformationGetterService = $userInformationGetterService;
        $this->userCoinRepository = $userCoinRepository;
        $this->usersRepository = $usersRepository;
        $this->coinFullInfoService = $coinFullInfoService;
    }

    public function execute(int $userId, int $userCoinId, float $coinsRequired, bool $short = false): bool
    {
        $coin = $this->coinFullInfoService->execute($userCoinId);
        $user = $this->userInformationGetterService->execute($userId);
        $coinCost = $coin->getQuote()->getPrice() * 100;
        $coinsCount = $user->getUserCoins()->getTotalCountByProductId($userCoinId);
        if (!$short) {
            Validate::userCoinChecker($coinsCount, $coinsRequired, $userCoinId);
        }
        $userCoin = new UserCoin(
            $userCoinId,
            $userId,
            $coinCost,
            $coin->getLogoUrl(),
            $coin->getName(),
            $coin->getSymbol(),
            new TransactionData($short ? 'SELL-SHORT' : 'SELL', new \DateTime(), $coinsRequired, null,$short ? true : null)
        );
        if (empty($_SESSION['error'])) {
            if ($this->userCoinRepository->insertCoin($userCoin)) {
                return $this->usersRepository->updateUserInformation('balance', $user->getBalance() + ($coinCost * $coinsRequired), $userId);

            } else {
                $_SESSION['error'][$userCoinId] = 'UserCoin database error';
            }
        }

        return false;
    }
}