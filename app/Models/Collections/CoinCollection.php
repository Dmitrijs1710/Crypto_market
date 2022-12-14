<?php

namespace App\Models\Collections;

use App\Models\Coin;

class CoinCollection
{
    private array $coins = [];

    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin){
            $this->add($coin);
        }
    }

    public function add(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getAll(): array
    {
        return $this->coins;
    }
}