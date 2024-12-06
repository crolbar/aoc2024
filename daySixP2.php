<?php
/**
 * @var string $input
 */

$input = trim($input);

$grid = explode("\n", $input);


function find_start_point($grid): array
{
    for ($r = 0; $r < sizeof($grid); $r++)
        for ($c = 0; $c < sizeof($grid); $c++)
            if ($grid[$r][$c] == '^')
                return [$r, $c];
}


[$sR, $sC]  = find_start_point($grid);


/*
 * [-1, 0] up
 * [1, 0] down
 * [0, -1] left
 * [0, 1] right
 */
const DIR_MAP = [
    '[-1,0]' => [0, 1],  // from up to righ
    '[1,0]' => [0, -1],  // from down to left
    '[0,-1]' => [-1, 0], // from left to up
    '[0,1]' => [1, 0],   // from right to down
];


// ....#.....
// ....+---+#
// ....|...|.
// ..#.|...|.
// ..+-C-+#|.
// ..|.S.|.|.
// .#+---C-+.
// .+----C+#.
// #+----+|..
// ......#|..


// ....#.....
// ....+---+#
// ....|...|.
// ..#.|...|.
// ..+-C-+#|.
// ..|.S.|.|.
// .#+O--C-+.
// .+----OO#.
// #O-O--+|..
// ......#O..



/**
 * @param array $grid
 * @param int $r
 * @param int $c
 * @param array $dir
 * @param array $start_pos if we go back to here we should have a loop
 * @return bool true if we have an loop false if not
 */
function check_loop(
    array $grid,
    int $r,
    int $c,
    array $dir,
    array $visited): bool
{
    $nR = $r + $dir[0];
    $nC = $c + $dir[1];

    if (!isset($visited[$r][$c])) {
        $visited[$r][$c] = $dir;
    } else
        if (
            $visited[$r][$c][0] == $dir[0]
                && $visited[$r][$c][1] == $dir[1]
        )
            return true;


    if ($nR < 0 || $nR >= sizeof($grid))
        return false;

    if ($nC < 0 || $nC >= strlen($grid[0]))
        return false;

    if ($grid[$nR][$nC] == 'T' || $grid[$nR][$nC] == '#') {
        $dir = DIR_MAP[json_encode($dir)];
        $nR = $r;
        $nC = $c;
    }

    // echo "going: r: $nR, c: $nC \n";
    if (check_loop($grid, $nR, $nC, $dir, $visited))
        return true;

    return false;
}

for ($r = 0; $r < sizeof($grid); $r++) {
    for ($c = 0; $c < strlen($grid[0]); $c++) {
        if ($grid[$r][$c] == '#')
            continue;

        $grid[$r][$c] = 'T';

        $loop = check_loop($grid, $sR, $sC, [-1, 0], []);
        if ($loop) {
            $grid[$r][$c] = 'O';
        } else {
            $grid[$r][$c] = '.';
        }
    }
}

echo implode("\n", $grid) . "\n";

$res = 0;

for ($r = 0; $r < sizeof($grid); $r++)
    for ($c = 0; $c < strlen($grid[0]); $c++)
        if ($grid[$r][$c] == 'O')
            $res++;

echo $res . "\n";
