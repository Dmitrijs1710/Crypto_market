<?php

namespace App\Services;

use App\Models\Coin;
use App\Repositories\CoinsFromApiRepository;

class CoinFullInfoService
{
    public function execute(string $id): Coin
    {
        return (new CoinsFromApiRepository())->getById($id);
    }
}