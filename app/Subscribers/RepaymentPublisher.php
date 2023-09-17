<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Models\Repayment;

class RepaymentPublisher
{
    /** @var RepaymentSubscriberInterface[] */
    private $subscribers;

    public function __construct(CheckLoanPaidSubscriber $checkLoanPaidSubscriber)
    {
        $this->subscribers[] = $checkLoanPaidSubscriber;
    }

    public function publish(string $previousState, Repayment $repayment): void
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->handle($previousState, $repayment);
        }
    }
}
