<?php

declare(strict_types=1);

namespace App\Loan\Repayment;

use DateTime;

class Deadline
{
    public static function fromDateTime(DateTime $dateTime): self
    {
        return new self($dateTime);
    }

    public function __construct(private DateTime $dateTime)
    {
    }

    public function asDateTime(): DateTime
    {
        return $this->dateTime;
    }
}
