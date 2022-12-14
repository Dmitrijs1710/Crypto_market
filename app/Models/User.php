<?php

namespace App\Models;

use App\Models\UserCoins\UserCoinCollection;

class User
{

    private string $name;
    private ?string $password;
    private string $eMail;
    private ?int $id;
    private int $balance;
    private ?UserCoinCollection $userCoins;

    public function __construct
    (
        string              $eMail,
        string              $name,
        int                 $balance = 0,
        ?UserCoinCollection $userCoins = null,
        ?string             $password = null,
        ?int                $id = null
    )
    {
        $this->name = $name;
        $this->password = $password;
        $this->eMail = $eMail;
        $this->id = $id;
        $this->balance = $balance;
        $this->userCoins = $userCoins;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getEMail(): string
    {
        return $this->eMail;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getUserCoins(): ?UserCoinCollection
    {
        return $this->userCoins;
    }


    public function setUserCoins(?UserCoinCollection $userCoins): void
    {
        $this->userCoins = $userCoins;
    }
}