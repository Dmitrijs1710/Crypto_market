<?php

namespace App\Models;


use App\Models\UserCoinProperties\TransactionData;

class UserCoin
{
    private ?int $id;
    private int $userId;
    private ?float $price;
    private ?string $logo;
    private string $name;
    private string $symbol;
    private TransactionData $userCoinTransaction;
    private int $productId;

    public function __construct(
        int    $productId,
        int    $userId,
        ?float    $price,
        ?string $logo,
        string $name,
        string $symbol,
        TransactionData $userCoinTransaction,
        ?int    $id = null
    )
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->price = $price;
        $this->logo = $logo;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->userCoinTransaction = $userCoinTransaction;
        $this->productId = $productId;
    }


    public function getId(): ?int
    {
        return $this->id;
    }



    public function getUserId(): int
    {
        return $this->userId;
    }


    public function getPrice(): ?float
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

    public function getUserCoinTransaction(): TransactionData
    {
        return $this->userCoinTransaction;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

}