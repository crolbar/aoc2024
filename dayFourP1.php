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
        if ($line[$x] == 'X')
            array_push($q, ['X', $y, $x, null]);
    }
}

//XMAS
$find_map = [
    "X" => "M",
    "M" => "A",
    "A" => "S",
    "S" => true,
];

$dirs = [
    [-1, 0], # up
    [1, 0],  # down
    [0, -1], # left
    [0, 1],  # right
    [-1, -1],# up-left
    [-1, 1], # up-right
    [1, -1], # down-left
    [1, 1],  # down-right
];

$num_lines = sizeof($lines);
$num_chars = strlen($lines[0]);

$c = 0;
while (sizeof($q) > 0)
{
    [$node, $y, $x, $word_dir] = array_shift($q);

    $next_node = $find_map[$node];

    if ($next_node === true) {
        $c++;
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
