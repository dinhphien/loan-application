<?php

declare(strict_types=1);

namespace App\Loan\Repayment\Scheduler;

use App\Loan\Money;
use App\Loan\Repayment\Deadline;
use App\Loan\Repayment\Repayment;
use App\Loan\Repayment\RepaymentCollection;
use App\Loan\Term;

class RepaymentScheduler implements SchedulerInterface
{
    public function __construct(
        private MoneyScheduler $moneyScheduler,
        private TimeScheduler $timeScheduler
    ) {
    }

    public function schedule(Money $money, Term $term): RepaymentCollection
    {
        $repaymentCollection = new RepaymentCollection();

        $amounts = $this->moneyScheduler->calculate($money->getAmount(), $term->asNumberOfWeek());
        $times = $this->timeScheduler->calculate($term->asNumberOfWeek());

        foreach ($amounts as $i => $amount) {
            $repaymentCollection->add(
                Repayment::fromParameters(
                    Money::fromAmount($amount),
                    Deadline::fromDateTime($times[$i])
                )
            );
        }

        return $repaymentCollection;
    }
}
