<?php

namespace App\Services;

class UserCoinsVariablesService
{
    private CoinFullInfoService $coinFullInfoService;
    private UserInformationGetterService $userInformationGetterService;

    public function __construct(CoinFullInfoService $coinFullInfoService, UserInformationGetterService $userInformationGetterService)
    {
        $this->coinFullInfoService = $coinFullInfoService;
        $this->userInformationGetterService = $userInformationGetterService;
    }


    public function execute(): array
    {
        if (!empty($_SESSION['id'])) {
            $user = $this->userInformationGetterService->execute($_SESSION['id']);
            $userCoins = [];
            if ($user->getUserCoins() != null) {
                foreach ($user->getUserCoins()->getUniqueId() as $id) {
                    $userCoinCount = $user->getUserCoins()->getTotalCountById($id);
                    $coin = $this->coinFullInfoService->execute($id);
                    $avg = $user->getUserCoins()->getAverageById($id) / 100;
                    if ($userCoinCount > 0) {
                        $userCoins[] = [
                            'count' => $userCoinCount,
                            'coin' => $coin,
                            'avg' => $avg,
                            'income' => $coin->getQuote()->getPrice() - $avg
                        ];
                    }
                }
            }
            return [
                'name' => $user->getName(),
                'email' => $user->getEMail(),
                'coins' => $userCoins,
                'userBalance' => $user->getBalance() / 100,
            ];
        }
        return [];
    }
}