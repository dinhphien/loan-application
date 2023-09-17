<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Models\Repayment;

interface RepaymentSubscriberInterface
{
    public function handle(string $previousState, Repayment $repayment): void;
}
