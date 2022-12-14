<?php

namespace App\Repositories;

use App\ApiClass;
use App\Models\Coin;
use App\Models\Collections\CoinCollection;
use App\Models\CoinProperty\Quote;

class CoinsFromApiRepository implements CoinsRepository
{

    public function getFirstTen(): CoinCollection
    {
        $coins = new CoinCollection();
        $response = (
        new ApiClass
        (
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest',
            [
                'start' => '1',
                'limit' => '10',
            ]
        )
        )->getResponse();
        $response = json_decode($response);
        $id = [];
        foreach ($response->data as $data) {
            $id[] = $data->id;
        }
        $responseMetadata = (
        new ApiClass
        (
            'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info',
            [
                'id' => implode(',', $id)
            ]
        )
        )->getResponse();
        $responseMetadata = json_decode($responseMetadata);

        foreach ($response->data as $data) {
            $coins->add(new Coin(
                $data->id,
                $data->name,
                $data->symbol,
                $data->cmc_rank,
                $data->circulating_supply,
                $data->total_supply,
                $responseMetadata->data->{$data->id}->logo,
                new Quote(
                    $data->quote->USD->price,
                    $data->quote->USD->volume_24h,
                    $data->quote->USD->percent_change_1h,
                    $data->quote->USD->percent_change_24h,
                    $data->quote->USD->percent_change_7d,
                    $data->quote->USD->market_cap,
                    $data->quote->USD->last_updated
                )
            ));
        }
        return $coins;
    }

    public function getById(int $id): ?Coin
    {
        $response = (
        new ApiClass
        (
            'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest',
            [
                'id' => $id
            ]
        )
        )->getResponse();
        $response = json_decode($response);
        $data = null;
        foreach ($response->data as $item) {
            if ($item->id == $id) {
                $data = $item;
                break;
            }
        }

        $responseMetadata = (
        new ApiClass(
            'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info',
            [
                'id' => $id
            ]
        )
        )->getResponse();
        $responseMetadata = json_decode($responseMetadata);
        return $data == null ? null : (new Coin(
            $data->id,
            $data->name,
            $data->symbol,
            $data->cmc_rank,
            $data->circulating_supply,
            $data->total_supply,
            $responseMetadata->data->{$data->id}->logo,
            new Quote(
                $data->quote->USD->price,
                $data->quote->USD->volume_24h,
                $data->quote->USD->percent_change_1h,
                $data->quote->USD->percent_change_24h,
                $data->quote->USD->percent_change_7d,
                $data->quote->USD->market_cap,
                $data->quote->USD->last_updated
            )
        ));
    }

    public function search(string $search): CoinCollection
    {
        $coins = new CoinCollection();
        $search = strtoupper($search);
        $response = (
        new ApiClass
        (
            'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest',
            [
                'symbol' => $search,
            ]
        )
        )->getResponse();
        $response = json_decode($response, true);
        $id = [];
        foreach ($response['data'][$search] as $data) {
            $id[] = $data['id'];
        }

        if (count($id) > 0) {
            $responseMetadata = (
            new ApiClass('https://pro-api.coinmarketcap.com/v2/cryptocurrency/info',
                [
                    'id' => implode(',', $id)
                ]
            )
            )->getResponse();
            $responseMetadata = json_decode($responseMetadata);
            foreach ($response['data'][$search] as $data) {
                $coins->add(new Coin(
                    $data['id'],
                    $data['name'],
                    $data['symbol'],
                    $data['cmc_rank'],
                    $data['circulating_supply'],
                    $data['total_supply'],
                    $responseMetadata->data->{$data['id']}->logo,
                    new Quote(
                        $data['quote']['USD']['price'],
                        $data['quote']['USD']['volume_24h'],
                        $data['quote']['USD']['percent_change_1h'],
                        $data['quote']['USD']['percent_change_24h'],
                        $data['quote']['USD']['percent_change_7d'],
                        $data['quote']['USD']['market_cap'],
                        $data['quote']['USD']['last_updated']
                    )
                ));

            }
        }
        return $coins;
    }
}