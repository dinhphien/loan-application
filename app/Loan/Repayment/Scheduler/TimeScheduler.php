<?php

declare(strict_types=1);

namespace App\Loan\Repayment\Scheduler;

use DateInterval;
use DateTime;

class TimeScheduler
{
    const WEEKLY_INTERVAL = 7;

    /**
     * @return DateTime[]
     */
    public function calculate(int $numberOfTimes): array
    {
        $times = [];
        $currentTime = new DateTime();

        for ($i = 1; $i <= $numberOfTimes; $i++) {
            $interval = DateInterval::createFromDateString($i * self::WEEKLY_INTERVAL.'day');
            $times[] = (clone $currentTime)->add($interval);
        }

        return $times;
    }
}
