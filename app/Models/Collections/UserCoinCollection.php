<?php

namespace App\Models\Collections;

use App\Models\UserCoin;

class UserCoinCollection
{
    private array $userCoins = [];

    public function __construct(array $userCoins = [])
    {
        foreach ($userCoins as $userCoin) {
            $this->add($userCoin);
        }
    }

    public function add(UserCoin $userCoin)
    {
        $this->userCoins[] = $userCoin;
    }

    public function getTotalCountByProductId(int $id): float
    {
        $result = 0;
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if ($userCoin->getProductId() == $id) {
                if ($userCoin->getUserCoinTransaction()->getOperationType() == 'BUY') {
                    $result += $userCoin->getUserCoinTransaction()->getAmount();
                } else if ($userCoin->getUserCoinTransaction()->getOperationType() == 'SEND' && $userCoin->getUserCoinTransaction()->getUserTo() == $_SESSION['id']){
                    $result += $userCoin->getUserCoinTransaction()->getAmount();
                } else if ($userCoin->getUserCoinTransaction()->getOperationType() == 'SELL' || $userCoin->getUserCoinTransaction()->getOperationType() == 'SEND') {
                    $result -= $userCoin->getUserCoinTransaction()->getAmount();
                }
            }
        }
        return $result;
    }

    public function getShortSell() :array
    {
        $result = [];
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if (
                $userCoin->getUserCoinTransaction()->getOpen()
            ) {
                $result[] = $userCoin;
            }
        }
        return $result;
    }

    public function getAll(): ?array
    {
        return $this->userCoins;
    }

    public function getByProductId(int $id): ?array
    {
        $result = [];
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if ($userCoin->getProductId() == $id) {
                $result[] = $userCoin;
            }
        }
        return count($result) > 0 ? $result : null;
    }

    public function getUniqueId(): array
    {
        $result = [];
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if (!in_array($userCoin->getProductId(), $result)) {
                $result[] = $userCoin->getProductId();
            }
        }
        return $result;
    }

    public function getAverageByProductId(int $id): float
    {
        $totalSum = 0;
        $totalAmount = 0;
        /** @var UserCoin $userCoin */
        foreach ($this->getByProductId($id) as $userCoin) {
            switch ($userCoin->getUserCoinTransaction()->getOperationType()) {
                case 'BUY':
                $totalSum += $userCoin->getPrice() * $userCoin->getUserCoinTransaction()->getAmount();
                $totalAmount += $userCoin->getUserCoinTransaction()->getAmount();
                break;
                case 'SELL':
                $totalSum -= $userCoin->getPrice() * $userCoin->getUserCoinTransaction()->getAmount();
                $totalAmount -= $userCoin->getUserCoinTransaction()->getAmount();
                break;
            }
        }
        return $totalAmount != 0 ? $totalSum/$totalAmount : 0;
    }
}