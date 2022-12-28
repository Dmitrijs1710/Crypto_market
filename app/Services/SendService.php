<?php

namespace App\Services;

use App\Models\UserCoin;
use App\Models\UserCoinProperties\TransactionData;
use App\Repositories\UserCoinRepository;
use App\Repositories\UsersRepository;
use App\Validate;
use DateTime;

class SendService
{
    private UserCoinRepository $userCoinRepository;
    private CoinFullInfoService $coinFullInfoService;
    private UsersRepository $usersRepository;

    public function __construct(
        UserCoinRepository $userCoinRepository,
        CoinFullInfoService $coinFullInfoService,
        UsersRepository $usersRepository
    )
    {
        $this->userCoinRepository = $userCoinRepository;
        $this->coinFullInfoService = $coinFullInfoService;
        $this->usersRepository = $usersRepository;
    }

    public function execute(int $userId, string $userToEmail, string $password, int $coinId, float $amount) :bool
    {
        $userReceiver = $this->usersRepository->getUserByEmail($userToEmail);
        if ($userReceiver == null)
        {
            $_SESSION['error']['email'] = 'Incorrect email';
        }

        $userSender = $this->usersRepository->getUserById($userId);
        if ($userReceiver->getId() == $userSender->getId()){
            $_SESSION['error']['email'] = "Can't send to self";
        }
        if(!password_verify($password, $userSender->getPassword()))
        {
            $_SESSION['error']['password'] = 'Incorrect password';
        }
        $coins = $this->userCoinRepository->getCoinCollectionByUserId($userId);
        $coinCount = $coins->getTotalCountByProductId($coinId);
        $coinFullInfo = $this->coinFullInfoService->execute($coinId);
        Validate::userCoinChecker($coinCount, $amount, $coinId);
        if (empty($_SESSION['error']))
        {
            if ($this->userCoinRepository->insertCoin
                (
                    new UserCoin(
                        $coinId,
                        $userId,
                        null,
                        $coinFullInfo->getLogoUrl(),
                        $coinFullInfo->getName(),
                        $coinFullInfo->getSymbol(),
                        new TransactionData('SEND',new DateTime(), $amount, $userReceiver->getId())
                    )
                )
            ){
                return true;
            }
        }
        return false;
    }
}