<?php
/**
 * @var string $input
 */

$input = trim($input);

$reports = explode("\n", $input);
$num_of_safe = 0;


function is_levels_safe(array $levels): bool
{
    $prev = $levels[0];
    $is_increasing = $levels[0] < $levels[1];

    for ($i = 1; $i < sizeof($levels); $i++)
    {
        $level = $levels[$i];

        if ($is_increasing && (int)$prev > (int)$level) {
            echo "non safe " . "curr: " . $level . " prev: " . $prev . "\n";
            return false;
        }

        if (!$is_increasing && (int)$prev < (int)$level) {
            echo "non safe " . "curr: " . $level . " prev: " . $prev . "\n";
            return false;
        }

        $diff = abs((int)$prev - (int)$level);
        if ($diff < 1 || $diff > 3) {
            echo "non safe diff " . $diff . " curr: " . $level . " prev: " . $prev . "\n";
            return false;
        }


        $prev = $level;
    }

    return true;
}

foreach ($reports as $report)
{
    $levels = explode(" ", $report);
    echo "\nnew level===\n" . json_encode($levels) . "\n";

    $is_safe = false;
    for ($i = 0; $i < sizeof($levels); $i++) {
        $new_levels = $levels;
        unset($new_levels[$i]);
        $new_levels = array_values($new_levels);

        if (is_levels_safe($new_levels)) {
            $is_safe = true;
            break;
        }
    }

    if ($is_safe) {
        $num_of_safe++;
        echo "safe level" . "\n";
    }
}

echo $num_of_safe;
