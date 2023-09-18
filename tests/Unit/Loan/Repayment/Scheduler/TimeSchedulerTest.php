<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment\Scheduler;

use App\Loan\Repayment\Scheduler\TimeScheduler;
use PHPUnit\Framework\TestCase;
use DateTime;

/**
 * @covers \App\Loan\Repayment\Scheduler\TimeScheduler
 */
class TimeSchedulerTest extends TestCase
{
    public function test_can_schedule_time_correctly_with_term_greater_than_one(): void
    {
        $subject = new TimeScheduler();
        $current = new DateTime();
        $term = rand(1, 10);

        $dates = $subject->calculate($term);

        $this->assertCount($term, $dates);

        for ($i = count($dates) - 1; $i >= 0; $i--) {
            if ($i === 0) {
                $this->assertEquals(TimeScheduler::WEEKLY_INTERVAL, $current->diff($dates[$i])->days);
                $this->assertTrue($current < $dates[$i]);
                continue;
            }

            $this->assertEquals(TimeScheduler::WEEKLY_INTERVAL, $dates[$i]->diff($dates[$i - 1])->days);
            $this->assertTrue($dates[$i] > $dates[$i - 1]);
        }


    }

}
