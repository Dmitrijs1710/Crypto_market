<?php

namespace App\Models\UserCoinProperties;

use DateTime;

class TransactionData
{
    private string $operationType;
    private ?int $userTo;
    private DateTime $date;
    private float $amount;
    private ?bool $open;

    public function __construct(string $operationType, DateTime $date, float $amount,  ?int $userTo = null, ?bool $open = null)
    {
        $this->operationType = $operationType;
        $this->userTo = $userTo;
        $this->date = $date;
        $this->amount = $amount;
        $this->open = $open;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function getUserTo(): ?int
    {
        return $this->userTo;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }
}