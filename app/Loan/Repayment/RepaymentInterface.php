<?php

declare(strict_types=1);

namespace App\Loan\Repayment;

use App\Loan\Money;

interface RepaymentInterface
{
    public function getMoney(): Money;

    public function getDeadline(): Deadline;
}
