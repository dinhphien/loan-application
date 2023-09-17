<?php

declare(strict_types=1);

namespace App\Loan;

use InvalidArgumentException;

class Money
{
    private float $amount;

    public static function fromAmount(float $amount): self
    {
        return new self($amount);
    }

    public function __construct(float $amount)
    {
        $this->ensureHasPositiveValue($amount);
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    private function ensureHasPositiveValue(float $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('The amount of Money needs to be greater than 0!');
        }
    }
}
