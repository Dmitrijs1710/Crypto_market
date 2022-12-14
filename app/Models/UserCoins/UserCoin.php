<?php

namespace App\Models\UserCoins;

class UserCoin
{
    private int $id;
    private float $count;
    private int $userId;
    private string $operation;
    private int $price;
    private string $logo;
    private string $name;
    private string $symbol;

    public function __construct(
        int    $id,
        int    $userId,
        string $operation,
        int    $price,
        float  $count,
        string $logo,
        string $name,
        string $symbol
    )
    {
        $this->id = $id;
        $this->count = $count;
        $this->userId = $userId;
        $this->operation = $operation;
        $this->price = $price;
        $this->logo = $logo;
        $this->name = $name;
        $this->symbol = $symbol;
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


    public function getSymbol(): string
    {
        return $this->symbol;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getLogo(): string
    {
        return $this->logo;
    }
}