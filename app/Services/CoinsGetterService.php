<?php

namespace App\Services;

use App\Models\Collections\CoinCollection;
use App\Repositories\CoinsFromApiRepository;
use App\Repositories\CoinsRepository;

class CoinsGetterService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {

        $this->coinsRepository = $coinsRepository;
    }

    public function execute(string $search = ''): CoinCollection
    {
        if ($search !== '') {
            return ($this->coinsRepository->search($search));
        }
        return ($this->coinsRepository->getFirstTen());
    }
}