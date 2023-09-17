<?php

declare(strict_types=1);

namespace App\Loan\Repayment\Scheduler;

use App\Loan\Money;
use App\Loan\Repayment\RepaymentCollection;
use App\Loan\Term;

interface SchedulerInterface
{
    public function schedule(Money $money, Term $term): RepaymentCollection;
}
