<?php
/**
 * @var string $input
 */

$input = trim($input);


$lines = explode("\n", $input);
$q = [];

// add all X-es to a queue
foreach ($lines as $y => $line)
{
    for ($x = 0; $x < strlen($line); $x++)
    {
        if ($line[$x] == 'M')
            array_push($q, ['M', $y, $x, null]);
    }
}


//XMAS
$find_map = [
    "M" => "A",
    "A" => "S",
    "S" => 1,
];

$dirs = [
    [-1, -1],# up-left
    [-1, 1], # up-right
    [1, -1], # down-left
    [1, 1],  # down-right
];

$num_lines = sizeof($lines);
$num_chars = strlen($lines[0]);

$special_char = "#";

$c = 0;
while (sizeof($q) > 0)
{
    [$node, $y, $x, $word_dir] = array_shift($q);

    $next_node = $find_map[$node];

    // we found a "MAS"
    if ($next_node === 1) {
        $bY = $y + (-1 * $word_dir[0]);
        $bX = $x + (-1 * $word_dir[1]);

        // check if the prev char is marked
        // if it is then we are crossing an 'A'
        // that has been marked as crossed
        // which guarantees us with an cross of two "MAS"-es
        if ($lines[$bY][$bX] == $special_char)
            $c++;

        // if not we just mark the prev char (which has to be 'A'
        $lines[$bY][$bX] = $special_char;
        continue;
    }

    foreach ($dirs as [$dir_y, $dir_x])
    {
        $nY = $y + $dir_y;
        $nX = $x + $dir_x;
        $next_word_dir = [$dir_y, $dir_x];

        // make sure we are in the grid
        if ($nY < 0 || $nY >= $num_lines)
            continue;
        if ($nX < 0 || $nX >= $num_chars)
            continue;

        // make sure the word is going in one direction
        if ($word_dir !== null && $next_word_dir !== $word_dir)
            continue;

        if ($lines[$nY][$nX] == $next_node)
            array_push($q, [$next_node, $nY, $nX, $next_word_dir]);
    }
}

echo $c;
