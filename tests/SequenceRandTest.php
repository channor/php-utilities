<?php

namespace Channor\Utilities\Tests;

use Channor\Utilities\SequenceRand;
use PHPUnit\Framework\TestCase;

class SequenceRandTest extends TestCase
{
    /**
     * @covers \Channor\Utilities\SequenceRand::__construct
     * @return void
     */
    public function testSequenceRand()
    {
        $seq = new SequenceRand(48, 6, 7, 10);
        $this->assertSame($seq->getCount(), 6);
        $this->assertSame($seq->getReducedCount(), 6);
        $this->assertSame($seq->getRemainingCount(), 5);
        $this->assertSame($seq->getReducedSum(), 48);
        $this->assertSame($seq->getCurrentSum(), 0);

        $first = $seq->getNext();

        $this->assertThat($first, $this->logicalAnd(
            $this->greaterThanOrEqual(7),
            $this->lessThanOrEqual(10)
        ));

        $this->assertSame($seq->getReducedSum(), (48 - $first));
        $this->assertSame($seq->getCurrentSum(), $first);
        $this->assertSame($seq->getReducedCount(), 5);
        $this->assertSame($seq->getRemainingCount(), 4);

        $second = $seq->getNext();

        $this->assertThat($second, $this->logicalAnd(
            $this->greaterThanOrEqual(7),
            $this->lessThanOrEqual(10)
        ));

        $this->assertSame($seq->getReducedSum(), (48 - $first - $second));
        $this->assertSame($seq->getCurrentSum(), ($first + $second));
        $this->assertSame($seq->getReducedCount(), 4);
        $this->assertSame($seq->getRemainingCount(), 3);

        $this->assertCount(2, $seq->getValues());

        $seq->next();
        $seq->next();
        $seq->next();
        $seq->next();

        $this->assertSame($seq->getCurrentSum(), 48);
        $this->assertSame($seq->getReducedSum(), 0);
        $this->assertSame($seq->getRemainingCount(), -1);
        $this->assertSame($seq->getReducedCount(), 0);

        $this->assertInstanceOf(SequenceRand::class, $seq->next());
        $this->assertFalse($seq->getNext());
    }

    /**
     * @covers \Channor\Utilities\SequenceRand
     * @return void
     */
    public function testSequenceRandRun()
    {
        $seq = new SequenceRand(48, 6, 7, 10);
        $seq->run();

        $this->assertSame(48, $seq->getCurrentSum());
        $this->assertCount(6, $seq->getValues());
        $this->assertTrue(max($seq->getValues()) <= 10);
        $this->assertTrue(max($seq->getValues()) >= 7);
        $this->assertTrue(min($seq->getValues()) <= 10);
        $this->assertTrue(min($seq->getValues()) >= 7);
    }

    /**
     * @covers \Channor\Utilities\SequenceRand::shuffle
     * @return void
     */
    public function testShuffle(): void
    {
        $seq = new SequenceRand(200, 75, 1, 10);
        $seq->run();
        $this->assertNotSame($seq->getValues(), $seq->shuffle()->getValues());
    }
}
