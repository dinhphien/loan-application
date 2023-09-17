<?php

declare(strict_types=1);

namespace App\Loan\Repayment;

use App\Loan\Money;

class Repayment implements RepaymentInterface
{
    public static function fromParameters(Money $money, Deadline $deadline): self
    {
        return new self($money, $deadline);
    }

    public function __construct(
        private Money $money,
        private Deadline $deadline
    ) {
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getDeadline(): Deadline
    {
        return $this->deadline;
    }
}
