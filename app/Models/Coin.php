<?php

namespace App\Models;

use App\Models\CoinProperty\Quote;

class Coin
{
    private int $id;
    private string $name;
    private string $symbol;
    private ?int $cmcRank;
    private ?int $circulatingSupply;
    private ?int $totalSupply;
    private ?string $logoUrl;
    private Quote $quote;

    public function __construct(
        int     $id,
        string  $name,
        string  $symbol,
        ?int    $cmcRank,
        ?int    $circulatingSupply,
        ?int    $totalSupply,
        ?string $logoUrl,
        Quote   $quote
    )
    {

        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->cmcRank = $cmcRank;
        $this->circulatingSupply = $circulatingSupply;
        $this->totalSupply = $totalSupply;
        $this->logoUrl = $logoUrl;
        $this->quote = $quote;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCirculatingSupply(): ?int
    {
        return $this->circulatingSupply;
    }

    public function getCmcRank(): ?int
    {
        return $this->cmcRank;
    }


    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getTotalSupply(): ?int
    {
        return $this->totalSupply;
    }


    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function getQuote(): Quote
    {
        return $this->quote;
    }
}