<?php

namespace App\Services;

use App\Models\Collections\CoinCollection;
use App\Repositories\CoinsFromApi;

class CoinsGetterService
{
    public function execute(string $search = '') :CoinCollection
    {
        if ($search!==''){
            return (new CoinsFromApi())->search($search);
        }
        return (new CoinsFromApi())->getFirstTen();
    }
}