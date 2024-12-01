<?php
/**
 * @var string $input
 */

$input = trim($input);
$pairs = explode("\n", $input);


$first_list = [];
$second_list = [];

foreach ($pairs as $pair)
{
    [$one, $two] = explode("   ", $pair);

    // echo "one: " . json_encode($one) . "\n";
    // echo "two: " . json_encode($two) . "\n\n";

    $first_list[] = $one;
    $second_list[] = $two;
}

// echo "first_list: " . json_encode($first_list) . "\n";
// echo "second_list: " . json_encode($second_list) . "\n";

sort($first_list, SORT_NUMERIC);
sort($second_list, SORT_NUMERIC);

// echo "first_list(sort): " . json_encode($first_list) . "\n";
// echo "second_list(sort): " . json_encode($second_list) . "\n";

$sum = 0;

for ($i = 0; $i < sizeof($first_list); $i++)
{
    $first = (int)$first_list[$i];
    $second = (int)$second_list[$i];

    $diff = abs($first - $second);

    // echo "diff: " . $diff . "\n";

    $sum += $diff;
}

echo $sum;
