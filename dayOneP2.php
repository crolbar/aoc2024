<?php
/**
 * @var string $input
 */

$input = trim($input);

$map = [];
$list = [];

$pairs = explode("\n", $input);

foreach ($pairs as $pair)
{
    [$one, $two] = explode("   ", $pair);

    $list[] = $one;

    if (!isset($map[$two])) {
        $map[$two] = 0;
    }

    $map[$two]++;
}

// echo json_encode($map) . "\n";
// echo json_encode($list) . "\n";

$sum = 0;

foreach ($list as $item)
{
    if (!isset($map[$item]))
    {
        continue;
    }

    $sum += (int)$item * (int)$map[$item];
}

echo $sum;
