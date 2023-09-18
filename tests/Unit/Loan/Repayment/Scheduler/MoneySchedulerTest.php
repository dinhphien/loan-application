<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment\Scheduler;

use App\Loan\Repayment\Scheduler\MoneyScheduler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Loan\Repayment\Scheduler\MoneyScheduler
 */
class MoneySchedulerTest extends TestCase
{

    /** @dataProvider provideDataForTestCanScheduleCorrectly */
    public function test_can_schedule_correctly(float $amount, int $term, array $expectedParts): void
    {
        $subject = new MoneyScheduler();

        $results = $subject->calculate($amount, $term);

        $this->assertEquals($expectedParts, $results);
        $this->assertCount($term, $results);
    }

    public function provideDataForTestCanScheduleCorrectly(): array
    {
        return [
            'term equals one' => [
                100000.0,
                1,
                [100000.0]
            ],
            'amount mods term is zero' => [
                100000.0,
                2,
                [50000.0, 50000.0]
            ],
            'amount mods term is not zero' => [
                100000.0,
                3,
                [33333.33, 33333.33, 33333.34]
            ]
        ];
    }

}
