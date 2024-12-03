<?php
/**
 * @var string $input
 */

$muls = explode("mul(", $input);

$sum = 0;

foreach ($muls as $mul)
{
    // find the closing
    if (!str_contains($mul, ")"))
        continue;

    $first_closing = strpos($mul, ")");
    $pair = substr($mul, 0, $first_closing);
    
    // make sure we have only one separator
    if (substr_count($pair, ",") != 1)
        continue;

    [$l, $r] = explode(",", $pair);

    // and finaly make sure that the separated values are numbers
    if (!is_numeric($l) || !is_numeric($r))
        continue;

    $sum += (int)$l * (int)$r;
}

echo $sum . "\n";
