<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment\Scheduler;

use App\Loan\Money;
use App\Loan\Repayment\Scheduler\MoneyScheduler;
use App\Loan\Repayment\Scheduler\RepaymentScheduler;
use App\Loan\Repayment\Scheduler\TimeScheduler;
use App\Loan\Term;
use PHPUnit\Framework\TestCase;
use DateTime;
/**
 * @covers \App\Loan\Repayment\Scheduler\RepaymentScheduler
 */
class RepaymentSchedulerTest extends TestCase
{
    private MoneyScheduler $moneyScheduler;

    private TimeScheduler $timeScheduler;

    private RepaymentScheduler $subject;
    protected function setUp(): void
    {
        $this->timeScheduler = $this->createMock(TimeScheduler::class);
        $this->moneyScheduler = $this->createMock(MoneyScheduler::class);

        $this->subject = new RepaymentScheduler(
            $this->moneyScheduler,
            $this->timeScheduler
        );
    }

    public function test_can_schedule_loan_correctly(): void
    {
        $money = Money::fromAmount(100.0);
        $term = Term::fromNumberOfWeek(3);

        $expectedMoneys = [33333.33, 33333.33, 33333.34];
        $this->moneyScheduler->expects($this->once())
            ->method('calculate')
            ->willReturn($expectedMoneys);

        $date1 = new DateTime('2023-08-19');
        $date2 = new DateTime('2023-08-26');
        $date3 = new DateTime('2023-09-03');
        $expectedDates = [$date1, $date2, $date3];
        $this->timeScheduler->expects($this->once())
            ->method('calculate')
            ->willReturn($expectedDates);

        $repaymentCollection = $this->subject->schedule($money, $term);

        $this->assertCount(3, $repaymentCollection);

        foreach($repaymentCollection as $i => $repayment) {
            $this->assertEquals($expectedDates[$i], $repayment->getDeadline()->asDateTime());
            $this->assertEquals($expectedMoneys[$i], $repayment->getMoney()->getAmount());
        }
    }

}
