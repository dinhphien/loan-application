<?php declare(strict_types=1);

namespace Tests\Unit\Loan;

use App\Loan\Term;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * @covers \App\Loan\Term
 */
class TermTest extends TestCase
{
    public function test_can_create_from_number_of_week(): void
    {
        $subject = Term::fromNumberOfWeek(4);

        $this->assertEquals(4, $subject->asNumberOfWeek());
    }

    public function test_will_throw_exception_if_number_of_week_is_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value of Term needs to be greater than 0!');

        Term::fromNumberOfWeek(0);
    }
}
