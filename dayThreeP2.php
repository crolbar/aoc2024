<?php

/**
 * @var string $input
 */

echo $input . "\n";

$enabled = [];

// split on all don't-s
$donts = explode("don't()", $input);

// push the first split since it is enbaled
array_push($enabled, $donts[0]);

foreach ($donts as $dont)
{
    // split substrings that start with don't ON do()
    $dos = explode("do()", $dont);

    // and just skip the first one
    for ($i = 1; $i < sizeof($dos); $i++)
    {
        $do = $dos[$i];
        array_push($enabled, $do);
    }
}

$enbaled_str = implode($enabled);
$muls = explode("mul(", $enbaled_str);

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
