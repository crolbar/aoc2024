<?php
/**
 * @var string $input
 */

$input = trim($input);
echo $input . "\n";

$blocks = [];
$curr_file_id = 0;

for ($i = 0; $i < strlen($input); $i++)
{
    $num = (int)$input[$i];

    for ($j = 0; $j < $num; $j++)
    {
        $blocks[] = (($i & 1) ? "." : (string)$curr_file_id);
    }

    if ($i & 1 > 0)
        $curr_file_id++;
}

echo implode("", $blocks) . "\n";


$left_most_free = 0;

$end_file = sizeof($blocks) - 1;

while (true)
{
    while (
        $left_most_free < sizeof($blocks)
            && $blocks[$left_most_free] != '.'
    )
        $left_most_free++;

    while (
        $end_file >= 0
            && $blocks[$end_file] == '.'
    )
        $end_file--;

    if ($left_most_free > $end_file)
        break;

    $blocks[$left_most_free] = $blocks[$end_file];
    $blocks[$end_file] = '.';

//     echo $blocks[$left_most_free] . " " . $left_most_free . "\n";
//     echo $blocks[$end_file] . " " . $end_file . "\n";
}

echo implode("", $blocks) . "\n";

$res = 0;
for ($i = 0; $blocks[$i] != '.'; $i++)
{
    $res += $i * (int)$blocks[$i];
}

echo $res . "\n";
