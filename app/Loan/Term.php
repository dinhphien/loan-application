<?php

declare(strict_types=1);

namespace App\Loan;

use InvalidArgumentException;

class Term
{
    private int $numberOfWeek;

    public static function fromNumberOfWeek(int $numberOfWeek): self
    {
        return new self($numberOfWeek);
    }

    public function __construct(int $numberOfWeek)
    {
        $this->ensureHasPositiveValue($numberOfWeek);
        $this->numberOfWeek = $numberOfWeek;
    }

    public function asNumberOfWeek(): int
    {
        return $this->numberOfWeek;
    }

    private function ensureHasPositiveValue(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('The value of Term needs to be greater than 0!');
        }
    }
}
