<?php

namespace App\Models\UserCoins;

class UserCoin
{
    private int $id;
    private float $count;
    private int $userId;
    private string $operation;
    private int $price;

    public function __construct(int $id, int $userId, string $operation, int $price, float $count)
    {
        $this->id = $id;
        $this->count = $count;
        $this->userId = $userId;
        $this->operation = $operation;
        $this->price = $price;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getCount(): float
    {
        return $this->count;
    }


    public function getOperation(): string
    {
        return $this->operation;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }


    public function getPrice(): int
    {
        return $this->price;
    }
}