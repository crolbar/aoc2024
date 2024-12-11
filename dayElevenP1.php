<?php
/**
 * @var string $input
 */

$input = trim($input);
echo $input . "\n";

$stones = explode(" ", $input);
// rules
//
// 0 -> 1
// even digit split in two like: 1234 would become 12 and 34
// else multiply the stone by 2024

function blink(&$stones)
{
    $init_stones = $stones;
    $added_stones = 0;
    foreach ($init_stones as $i => $stone)
    {
        // so i can use $i normaly
        $i = $i + $added_stones;

        // echo $stone . "\n";

        if ($stone == '0')
        {
            $stones[$i] = '1';

            continue;
        }

        if ((strlen($stone) & 1) === 0)
        {
            [$fh, $sh] = str_split($stone, strlen($stone) / 2);

            $stones[$i] = (string)(int)$sh;
            array_splice($stones, $i, 0, (string)(int)$fh);

            $added_stones++;

            // echo $fh . "|";
            // echo $sh . "\n";

            continue;
        }

        $stones[$i] = (string)($stone * 2024);
    }
}

for ($i = 0; $i < 25; $i++)
{
    echo "iter: $i\n";
    // echo "init stones: " . json_encode($stones) . "\n";
    blink($stones);
    // echo "\n";
}

echo sizeof($stones);
