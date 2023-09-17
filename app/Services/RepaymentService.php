<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\LoanLogicException;
use App\Loan\Money;
use App\Models\Repayment;
use App\Subscribers\RepaymentPublisher;

class RepaymentService
{
    public function __construct(private RepaymentPublisher $publisher)
    {
    }

    public function find(int $repaymentId): Repayment
    {
        return Repayment::findOrFail($repaymentId);
    }

    /**
     * @throws LoanLogicException
     */
    public function pay(Repayment $repayment, Money $money): void
    {
        if ($repayment->status === Repayment::PAID) {
            throw new LoanLogicException('Repayment already paid!');
        }

        if ($repayment->status === Repayment::PENDING) {
            throw new LoanLogicException('repayment is not approved yet!');
        }

        if ($repayment->amount > $money->getAmount()) {
            throw new LoanLogicException('money is not enough for this repayment!');
        }

        $repayment->status = Repayment::PAID;
        $repayment->save();

        $this->publisher->publish(Repayment::PENDING, $repayment);
    }
}
