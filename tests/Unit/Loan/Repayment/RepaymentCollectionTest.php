<?php declare(strict_types=1);

namespace Tests\Unit\Loan\Repayment;

use App\Loan\Repayment\Repayment;
use App\Loan\Repayment\RepaymentCollection;
use PHPUnit\Framework\TestCase;

/** @covers \App\Loan\Repayment\RepaymentCollection */
class RepaymentCollectionTest extends TestCase
{
    public function test_can_add_repayment(): void
    {
        $subject = new RepaymentCollection();
        $repayment = $this->createMock(Repayment::class);

        $subject->add($repayment);

        $this->assertCount(1, $subject);
        $this->assertEquals([0 => $repayment], iterator_to_array($subject));
    }
}
