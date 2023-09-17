<?php

namespace App\Providers;

use App\Loan\Repayment\Scheduler\RepaymentScheduler;
use App\Loan\Repayment\Scheduler\SchedulerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SchedulerInterface::class, RepaymentScheduler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
