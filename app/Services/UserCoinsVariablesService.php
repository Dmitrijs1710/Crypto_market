<?php

namespace App\Services;

use App\Models\UserCoin;

class UserCoinsVariablesService
{
    private CoinFullInfoService $coinFullInfoService;
    private UserInformationGetterService $userInformationGetterService;

    public function __construct(CoinFullInfoService $coinFullInfoService, UserInformationGetterService $userInformationGetterService)
    {
        $this->coinFullInfoService = $coinFullInfoService;
        $this->userInformationGetterService = $userInformationGetterService;
    }


    public function execute(?int $session, int $coinId = null): array
    {
        if ($session !=null ) {
            $user = $this->userInformationGetterService->execute($session);
            $userCoins = [];
            $shorts = [];
            if ($user->getUserCoins() != null) {
                if ($coinId != null) {
                    $coinId = [$coinId];
                    $shortsGet = [];
                } else {
                    $coinId = $user->getUserCoins()->getUniqueId();
                    $shortsGet = $user->getUserCoins()->getShortSell();
                }
                foreach ($coinId as $id) {
                    $userCoinCount = $user->getUserCoins()->getTotalCountByProductId($id);
                    $coin = $this->coinFullInfoService->execute($id);
                    $avg = $user->getUserCoins()->getAverageByProductId($id) / 100;
                    if ($userCoinCount > 0) {
                        $userCoins[] = [
                            'count' => $userCoinCount,
                            'coin' => $coin,
                            'avg' => $avg,
                            'income' => $coin->getQuote()->getPrice() - $avg
                        ];
                    }
                }

                /** @var userCoin $item */
                foreach ($shortsGet as $item){
                    $id = $item->getProductId();
                    $userCoinCount = $item->getUserCoinTransaction()->getAmount();
                    $coin = $this->coinFullInfoService->execute($id);
                    $uniqueId = $item->getId();
                    if ($userCoinCount > 0) {
                        $shorts[] = [
                            'count' => $userCoinCount,
                            'coin' => $coin,
                            'avg' => $item->getPrice() / 100,
                            'income' => ($coin->getQuote()->getPrice() - $item->getPrice()/100),
                            'uniqueId' => $uniqueId
                        ];
                    }
                }
            }
            return [
                'coins' => $userCoins,
                'shorts' => $shorts
            ];
        }
        return [];
    }
}