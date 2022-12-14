<?php

namespace App\Repositories;

use App\Models\Coin;
use App\Models\Collections\CoinCollection;

interface CoinsRepository
{
    public function getFirstTen() :CoinCollection;
    public function getById(int $id) :?Coin;
    public function search(string $search) :CoinCollection;
}