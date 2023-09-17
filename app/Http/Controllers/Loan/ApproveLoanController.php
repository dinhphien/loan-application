<?php

namespace App\Http\Controllers\Loan;

use App\Exceptions\LoanLogicException;
use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApproveLoanController extends Controller
{
    public function __construct(private LoanService $loanService)
    {
    }

    public function __invoke(int $loanId): JsonResponse
    {
        if (! Gate::allows('is_admin', Auth::user())) {
            return new JsonResponse([
                'message' => 'Missing permission!',
            ], 403);
        }

        try {
            $loan = $this->loanService->find($loanId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse([
                'message' => 'Loan not found!',
            ], 400);
        }

        try {
            $loan = $this->loanService->approve($loan);
        } catch (LoanLogicException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Approved loan successfully!',
            'loan' => $loan,
        ]);
    }
}
