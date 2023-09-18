<?php declare(strict_types=1);

namespace Tests\Unit\Loan;

use App\Loan\Money;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * @covers \App\Loan\Money
 */
class MoneyTest extends TestCase
{
    public function test_can_create_from_amount(): void
    {
        $subject = Money::fromAmount(10000.0);

        $this->assertSame(10000.0, $subject->getAmount());
    }

    public function test_will_throw_exception_if_amount_not_greater_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The amount of Money needs to be greater than 0!');

        Money::fromAmount(0);
    }
}
