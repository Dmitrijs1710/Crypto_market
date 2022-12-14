<?php

namespace App\Models\UserCoins;

class UserCoinCollection
{
    public array $userCoins = [];

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

    public function getTotalCountById(int $id): float
    {
        $result = 0;
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if ($userCoin->getId() == $id) {
                if ($userCoin->getOperation() == 'BUY') {
                    $result += $userCoin->getCount();
                } else {
                    $result -= $userCoin->getCount();
                }
            }
        }
        return $result;
    }

    public function getAll(): ?array
    {
        return $this->userCoins;
    }

    public function getById(int $id): ?array
    {
        $result = [];
        /** @var UserCoin $userCoin */
        foreach ($this->userCoins as $userCoin) {
            if ($userCoin->getId() == $id) {
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
            if (!in_array($userCoin->getId(), $result)) {
                $result[] = $userCoin->getId();
            }
        }
        return $result;
    }

    public function getAverageById(int $id): float
    {
        $totalSum = 0;
        /** @var UserCoin $userCoin */
        foreach ($this->getById($id) as $userCoin) {
            if ($userCoin->getOperation() === 'BUY') {
                $totalSum += $userCoin->getPrice() * $userCoin->getCount();
            } else {
                $totalSum -= $userCoin->getPrice() * $userCoin->getCount();
            }
        }
        return round($totalSum / $this->getTotalCountById($id));
    }
}