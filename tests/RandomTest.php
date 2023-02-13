<?php

namespace Channor\Tests;

use Channor\Utilities\Random;
use PHPUnit\Framework\TestCase;

class RandomTest extends TestCase
{

    /**
     * @covers Random::randIntervalsMax
     * @return void
     * @throws \Exception
     */
    public function testRandIntervalsMax(): void
    {
        $rand = Random::randIntervalsMax(48, 6, 7, 10);
        $this->assertSame(array_sum($rand), 48.0);
        $this->assertSame(count($rand), 6);
        $this->assertTrue(max($rand) <= 10);
        $this->assertTrue(min($rand) >= 7);

        $rand = Random::randIntervalsMax(20, 2, 7, 10);
        $this->assertSame(array_sum($rand), 20.0);
        $this->assertSame(count($rand), 2);
        $this->assertTrue(max($rand) <= 10);
        $this->assertTrue(min($rand) >= 7);
        $this->assertSame($rand[0], 10.0);
    }

    /**
     * @covers Random::randIntervalsMax()
     * @return void
     */
    public function testRandIntervalsMaxFails(): void
    {
        $this->expectException(\Exception::class);
        Random::randIntervalsMax(48, 6, 11, 10);
    }

    /**
     * @covers Random::randIntervalsMax()
     * @return void
     */
    public function testRandIntervalsMaxFailsTargetSumNotInRange(): void
    {
        $this->expectException(\Exception::class);
        Random::randIntervalsMax(70, 6, 5, 10);
    }
}
