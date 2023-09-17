<?php

declare(strict_types=1);

namespace App\Loan\Repayment;

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, RepaymentInterface>
 */
class RepaymentCollection implements IteratorAggregate
{
    /** @var RepaymentInterface[] */
    private array $repaymentItems = [];

    private int $count = 0;

    public function __construct()
    {
    }

    public function add(RepaymentInterface $repayment): void
    {
        $this->repaymentItems[] = $repayment;
        $this->count = $this->count + 1;
    }

    public function count(): int
    {
        return $this->count;
    }

    /** @return ArrayIterator<int, RepaymentInterface> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->repaymentItems);
    }
}
