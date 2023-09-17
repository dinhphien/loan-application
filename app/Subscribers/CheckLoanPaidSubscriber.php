<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Models\Loan;
use App\Models\Repayment;

class CheckLoanPaidSubscriber implements RepaymentSubscriberInterface
{
    public function handle(string $previousState, Repayment $repayment): void
    {
        if ($previousState === $repayment->status) {
            return;
        }

        if ($repayment->status !== Repayment::PAID) {
            return;
        }

        $check = Repayment::where('loan_id', $repayment->loan_id)
            ->where('status', '!=', Repayment::PAID)
            ->get()
            ->count();

        if ($check !== 0) {
            return;
        }

        Loan::where('id', $repayment->loan_id)
            ->update(['status' => Loan::PAID]);
    }
}
