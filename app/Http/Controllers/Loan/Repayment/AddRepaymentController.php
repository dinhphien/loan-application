<?php

namespace App\Http\Controllers\Loan\Repayment;

use App\Exceptions\LoanLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\AddRepaymentRequest;
use App\Loan\Money;
use App\Services\RepaymentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AddRepaymentController extends Controller
{
    public function __construct(private RepaymentService $repaymentService)
    {
    }

    public function __invoke(AddRepaymentRequest $request, int $repaymentId): JsonResponse
    {
        try {
            $repayment = $this->repaymentService->find($repaymentId);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse([
                'message' => 'Repayment not found!',
            ], 400);
        }

        try {
            $this->authorize('update', $repayment);
        } catch (AuthorizationException $exception) {
            return new JsonResponse([
                'message' => 'Permission denied!',
            ], 403);
        }

        $money = Money::fromAmount(
            $request->validated('amount')
        );

        try {
            $this->repaymentService->pay($repayment, $money);
        } catch (LoanLogicException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Pay repayment successfully!',
        ]);
    }
}
