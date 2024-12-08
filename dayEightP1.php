<?php
/**
 * @var string $input
 */

$input = trim($input);

$lines = explode("\n", $input);
$anthenas = [];

for ($r = 0; $r < sizeof($lines); $r++)
{
    for ($c = 0; $c < sizeof($lines); $c++)
    {
        if ($lines[$r][$c] != '.')
        {
            $anthenas[$lines[$r][$c]][] = [$r, $c];
        }
    }
}

// echo json_encode($anthenas) . "\n";

foreach ($anthenas as $k => $points)
{
    foreach ($points as $point1)
    {
        foreach ($points as $point2)
        {
            if ($point1[0] == $point2[0] && $point1[1] == $point2[1])
                continue;

            $r1 = $point1[0];
            $c1 = $point1[1];
            $r2 = $point2[0];
            $c2 = $point2[1];

            $row_diff    = abs($r1 - $r2);
            // if the top anthena is to the left
            $is_top_left = (($r1 < $r2 && $c1 < $c2) || ($r1 > $r2 && $c1 > $c2)) ? true : false;
            $col_diff    = abs($c1 - $c2);

            // ta -> top antinode
            $taR = min($r1, $r2) - $row_diff;
            $taC = (($r1 < $r2) ? $c1 : $c2) + ((($is_top_left) ? -1 : 1) * $col_diff);
            $baR = max($r1, $r2) + $row_diff;
            $baC = (($r1 > $r2) ? $c1 : $c2) + ((($is_top_left) ? 1 : -1) * $col_diff);

            if ($taR >= 0 && $taR < sizeof($lines)
                && $taC >= 0 && $taC < strlen($lines[0]))
                $lines[$taR][$taC] = '#';

            if ($baR >= 0 && $baR < sizeof($lines)
                && $baC >= 0 && $baC < strlen($lines[0]))
                $lines[$baR][$baC] = '#';
            
        //     echo "pair: " . json_encode($point1) . " | " . json_encode($point2) . "\n";
        //     echo "taR: $taR, taC: $taC \nbaR: $baR, baC: $baC\n\n";
        }
    }
}

$lines_with_anti = implode("\n", $lines);
// echo  $lines_with_anti . "\n";


$res = 0;
for ($i = 0; $i < strlen($lines_with_anti); $i++)
{
    if ($lines_with_anti[$i] == '#')
        $res++;
}

echo $res . "\n";
