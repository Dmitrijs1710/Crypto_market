<?php

namespace App\Services;

use App\Models\Coin;
use App\Repositories\CoinsFromApi;

class CoinFullInfoService
{
    public function execute(string $id) :Coin
    {
        return (new CoinsFromApi())->getById($id);
    }
}