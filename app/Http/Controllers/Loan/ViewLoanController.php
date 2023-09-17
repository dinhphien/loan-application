<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ViewLoanController extends Controller
{
    public function __construct(private LoanService $loanService)
    {
    }

    public function __invoke(int $loanId): JsonResponse
    {
        try {
            $loan = $this->loanService->find($loanId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse([
                'message' => 'Loan not found!',
            ], 400);
        }

        try {
            $this->authorize('view', $loan);
        } catch (AuthorizationException $exception) {
            return new JsonResponse([
                'message' => 'Permission denied!',
            ], 403);
        }

        $loan = $this->loanService->load($loanId);

        return new JsonResponse([
            'loan' => $loan,
        ]);
    }
}
