<?php

declare(strict_types=1);

namespace App\Loan\Repayment\Scheduler;

class MoneyScheduler
{
    /**
     * @return float[]
     */
    public function calculate(float $total, int $numberOfTimes): array
    {
        $medium = round($total / $numberOfTimes, 2, PHP_ROUND_HALF_DOWN);

        $amounts = [];
        $remain = $total;

        for ($i = 1; $i <= $numberOfTimes; $i++) {
            if ($i === $numberOfTimes) {
                $amounts[] = $remain;
                break;
            }

            $amounts[] = $medium;
            $remain = $remain - $medium;
        }

        return $amounts;
    }
}
