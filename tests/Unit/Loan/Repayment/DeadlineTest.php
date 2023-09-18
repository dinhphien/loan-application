<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment;

use App\Loan\Repayment\Deadline;
use PHPUnit\Framework\TestCase;
use DateTime;

/**
 * @covers \App\Loan\Repayment\Deadline
 */
class DeadlineTest extends TestCase
{
    public function test_can_create_from_date(): void
    {
        $currentTime = new DateTime();
        $subject = Deadline::fromDateTime($currentTime);

        $this->assertSame($currentTime, $subject->asDateTime());
    }
}
