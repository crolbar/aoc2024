<?php
/**
 * @var string $input
 */

$input = trim($input);

$reports = explode("\n", $input);
$num_of_safe = 0;

foreach ($reports as $report)
{
    $levels = explode(" ", $report);

    $prev = $levels[0];
    $is_increasing = $levels[0] < $levels[1];
    for ($i = 1; $i < sizeof($levels); $i++)
    {
        $level = $levels[$i];

        if ($is_increasing && (int)$prev > (int)$level)
            continue 2;

        if (!$is_increasing && (int)$prev < (int)$level)
            continue 2;

        $diff = abs((int)$prev - (int)$level);
        if ($diff < 1 || $diff > 3)
            continue 2;

        $prev = $level;
    }

    $num_of_safe++;
}

echo $num_of_safe;
