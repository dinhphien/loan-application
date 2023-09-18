<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment;

use App\Loan\Money;
use App\Loan\Repayment\Deadline;
use App\Loan\Repayment\Repayment;
use PHPUnit\Framework\TestCase;
use DateTime;

/** @covers \App\Loan\Repayment\Repayment */
class RepaymentTest extends TestCase
{
    public function test_can_create_from_parameters(): void
    {
        $currentTime = new DateTime();
        $subject = Repayment::fromParameters(
            Money::fromAmount(10.0),
            Deadline::fromDateTime($currentTime)
        );

        $this->assertSame(10.0, $subject->getMoney()->getAmount());
        $this->assertSame($currentTime, $subject->getDeadline()->asDateTime());
    }
}
