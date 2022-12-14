<?php

namespace App\Models\CoinProperty;

class Quote
{
    private ?float $price;
    private ?float $volume24h;
    private ?float $percentChange1h;
    private ?float $percentChange24h;
    private ?float $percentChange7d;
    private ?float $marketCap;
    private string $lastUpdated;

    public function __construct(
        ?float $price,
        ?float $volume24h,
        ?float $percentChange1h,
        ?float $percentChange24h,
        ?float $percentChange7d,
        ?float $marketCap,
        string $lastUpdated
    )
    {

        $this->price = $price;
        $this->volume24h = $volume24h;
        $this->percentChange1h = $percentChange1h;
        $this->percentChange24h = $percentChange24h;
        $this->percentChange7d = $percentChange7d;
        $this->marketCap = $marketCap;
        $this->lastUpdated = $lastUpdated;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    public function getMarketCap(): ?float
    {
        return $this->marketCap;
    }

    public function getPercentChange1h(): ?float
    {
        return $this->percentChange1h;
    }

    public function getPercentChange7d(): ?float
    {
        return $this->percentChange7d;
    }

    public function getPercentChange24h(): ?float
    {
        return $this->percentChange24h;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getVolume24h(): ?float
    {
        return $this->volume24h;
    }
}