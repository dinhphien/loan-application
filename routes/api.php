<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Loan\ApproveLoanController;
use App\Http\Controllers\Loan\CreateLoanController;
use App\Http\Controllers\Loan\Repayment\AddRepaymentController;
use App\Http\Controllers\Loan\ViewLoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group([
    'middleware' => 'auth:api'
], function (){
    Route::post('/loans', CreateLoanController::class);
    Route::post('/loans/{loanId}/approve', ApproveLoanController::class)->where('loanId', '[0-9]+');
    Route::get('/loans/{loanId}', ViewLoanController::class)->where('loanId', '[0-9]+');

    Route::post('repayments/{repaymentId}', AddRepaymentController::class)
        ->where('repaymentId', '[0-9]+');
});
