<?php
/**
 * @author Christian Andreassen
 * @license MIT
 */

namespace Channor\Utilities;

/**
 * A class to generate multiple random numbers in a sequence.
 * Create a new class. Use run() to finnish the sequence, or use
 * next() to generate the next random number. getNext() generates
 * the next number and returns the value.
 *
 * @todo Decimal choices.
 * @todo Allow $count = null. Run loop until $max_sum is reached.
 */
class SequenceRand
{
    protected mixed $max_sum;
    protected mixed $min_random_value;
    protected mixed $max_random_value;
    protected mixed $count;
    /**
     * Holds the generated values
     * @var array
     */
    protected array $values = [];
    /**
     * The reduced count after next()
     * @var int
     */
    protected int   $reduced_count;
    /**
     * Count remaining i future after the current run
     * @var int
     */
    protected int   $remaining_count;
    /**
     * Sum of all generated values.
     * @var int
     */
    protected int   $current_sum;
    /**
     * What is left of max_sum. max_sum subtracted current sum.
     * @var int|float
     */
    protected int   $reduced_sum;
    /**
     * To avoid infinite loop. Can be changed with setTimeout()
     * @var int
     */
    protected null|int   $timeout = 30;

    /**
     * @param int|float $max_sum The maximum sum to reach
     * @param int $count How many times to generate a random value
     * @param int|float $min_random_value Min value to be generated
     * @param int|float $max_random_value Max value to be generated
     */
    public function __construct(
        int|float $max_sum,
        int       $count,
        int|float $min_random_value,
        int|float $max_random_value,
    ) {
        $this->max_sum = $max_sum;
        $this->count = $count;
        $this->min_random_value = $min_random_value;
        $this->max_random_value = $max_random_value;
        $this->reduced_count = $count;
        $this->remaining_count = $count-1;
        $this->reduced_sum = $max_sum;
        $this->current_sum = 0;
    }

    /**
     * Runs until the sequence is finnished.
     * @return $this|void
     */
    public function run()
    {
        $start = time();
        while ($this->reduced_count > 0) {
            if(($this->timeout !== null) AND (time() > ($start + $this->timeout))) {
                exit;
            }
            $this->next();
        }

        return $this;
    }

    public function getNext()
    {
        if(($this->getReducedCount() === 0) OR ($this->getCurrentSum() === $this->max_sum)) {
            return false;
        }
        $next = mt_rand($this->getCurrentMinValue(), $this->getCurrentMaxValue());
        $this->insertValue($next);
        $this->decreaseCounts();
        $this->updateSumProperties($next);
        return $next;
    }

    public function next()
    {
        $this->getNext();
        return $this;
    }

    /**
     * Shuffle the values.
     * The latest values might have less difference between min_random_value and max_random_value
     * @return $this
     */
    public function shuffle(): static
    {
        shuffle($this->values);
        return $this;
    }

    protected function decreaseCounts()
    {
        $this->reduced_count--;
        $this->remaining_count--;
        return $this;
    }

    public function getCurrentMaxValue()
    {
        $current = $this->reduced_sum - ($this->remaining_count * $this->min_random_value);
        if ($current > $this->max_random_value) $current = $this->max_random_value;

        return $current;
    }

    public function getCurrentMinValue()
    {
        $current = $this->reduced_sum - ($this->remaining_count * $this->max_random_value);
        if ($current < $this->min_random_value) $current = $this->min_random_value;

        return $current;
    }

    /**
     * @return mixed
     */
    public function getMaxSum(): mixed
    {
        return $this->max_sum;
    }

    /**
     * @return mixed
     */
    public function getMinRandomValue(): mixed
    {
        return $this->min_random_value;
    }

    /**
     * @return mixed
     */
    public function getMaxRandomValue(): mixed
    {
        return $this->max_random_value;
    }

    /**
     * @return mixed
     */
    public function getCount(): mixed
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return int
     */
    public function getReducedCount(): int
    {
        return $this->reduced_count;
    }

    /**
     * @return int
     */
    public function getRemainingCount(): int
    {
        return $this->remaining_count;
    }

    /**
     * @return int
     */
    public function getCurrentSum(): int
    {
        return $this->current_sum;
    }

    /**
     * @return int
     */
    public function getReducedSum(): int
    {
        return $this->reduced_sum;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    protected function updateSumProperties($value): void
    {
        $this->current_sum += $value;
        $this->reduced_sum -= $value;
    }

    protected function insertValue($value)
    {
        $this->values[] = $value;
    }
}
