<?php

namespace Channor\Utilities;

class Random
{

    /**
     * Generate a random float/decimal between min and max value in multiple intervals with a max
     * sum per interval.
     *
     * I.e.: echo implode(', ', Random::randIntevalsMax(48, 6, 7, 10)) // 7.5, 9.5, 8, 7.5, 8, 7.5
     *
     * @param float $target_sum
     * @param float $intervals
     * @param float $min
     * @param float $max
     * @return array
     * @throws \Exception
     * @TODO Set decimal precision and choose how to round last digit.
     */
    public static function randIntervalsMax(
        float       $target_sum,
        float       $intervals,
        float       $min,
        float       $max,
    ): array
    {

        /** @float $average_number The avg number of the target avg divided with the intervals */
        $average_number = $target_sum / $intervals;

        /* Check if it is possible to reach the target avg */
        if($min > $max) {
            throw new \Exception("Min shall be less than or equal to Max");
        } elseif ($average_number < $min OR $average_number > $max) {
            throw new \Exception('Target sum. is out of range.');
        }

        /** @array $numbers Numbers made are store in an array to be returned. */
        $numbers = [];

        for ($i = 1; $i <= $intervals; $i++) {
            /** @integer $coming_intervals Number of intervals/loops after the current loop */
            $coming_intervals = $intervals - $i;
            /** @integer $sum_left Sum left to calculate min and max */
            $sum_left = !empty($numbers) ? $target_sum - array_sum($numbers) : $target_sum;

            $current_max = $sum_left - ($coming_intervals * $min);
            if ($current_max > $max) $current_max = $max;

            $current_min = $sum_left - ($coming_intervals * $max);
            if ($current_min < $min) $current_min = $min;

            $next = (round((mt_rand($current_min*10, $current_max*10) / 10) * 2) / 2);

            $numbers[] = (float)$next;
        }

        return $numbers;
    }
}