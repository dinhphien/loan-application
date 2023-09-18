<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\CreateLoanRequest;
use App\Loan\Money;
use App\Loan\Term;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class CreateLoanController extends Controller
{
    public function __construct(
        private LoanService $loanService
    ) {

    }

    public function __invoke(CreateLoanRequest $request): JsonResponse
    {
        $term = Term::fromNumberOfWeek(
            (int) $request->validated('term')
        );

        $money = Money::fromAmount(
            (float) $request->validated('amount')
        );

        $loan = $this->loanService->init($money, $term);

        return new JsonResponse(
            [
                'message' => 'Loan created successfully',
                'loan' => $loan,
            ]
        );

    }
}
