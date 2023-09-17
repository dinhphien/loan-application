<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\LoanLogicException;
use App\Loan\Money;
use App\Loan\Repayment\Scheduler\SchedulerInterface;
use App\Loan\Term;
use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LoanService
{
    public function __construct(
        private SchedulerInterface $scheduler
    ) {

    }

    public function init(Money $money, Term $term): Loan
    {
        $repayments = $this->scheduler->schedule($money, $term);

        $userId = Auth::user()->getAuthIdentifier();
        $loan = Loan::create(
            [
                'amount' => $money->getAmount(),
                'term' => $term->asNumberOfWeek(),
                'user_id' => $userId,
                'status' => Loan::PENDING,
            ]
        );

        $repaymentData = [];
        /** @var \App\Loan\Repayment\Repayment $repayment */
        foreach ($repayments as $repayment) {
            $repaymentData[] = [
                'amount' => $repayment->getMoney()->getAmount(),
                'deadline' => $repayment->getDeadline()->asDateTime(),
                'user_id' => $userId,
                'loan_id' => $loan->id,
                'status' => Repayment::PENDING,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        Repayment::insert($repaymentData);

        return $loan;
    }

    public function find(int $loanId): Loan
    {
        return Loan::findOrFail($loanId);
    }

    /**
     * @throws LoanLogicException
     */
    public function approve(Loan $loan): Loan
    {
        if ($loan->status !== Loan::PENDING) {
            throw new LoanLogicException('Loan already approved!');
        }

        $loan->status = Loan::APPROVED;
        $loan->save();

        Repayment::where('loan_id', $loan->id)
            ->update(['status' => Repayment::APPROVED]);

        return $loan;
    }

    public function load(int $loanId): Model
    {
        return Loan::with('repayments')->find($loanId);
    }
}
