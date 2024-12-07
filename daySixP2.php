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


//   0123456789
// 0 ....#.....
// 1 ....+---+#
// 2 ....|...|.
// 3 ..#.|...|.
// 4 ..+-C-+#|.
// 5 ..|.S.|.|.
// 6 .#+O--C-+.
// 7 .+----OO#.
// 8 #O-O--+|..
// 9 ......#O..



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

    if (check_loop($grid, $nR, $nC, $dir, $visited))
        return true;

    return false;
}

/**
 * @param array $grid
 * @param int $r
 * @param int $c
 * @param array $dir
 * @return bool true if we reached the end false if not
 */
function walk(array &$grid, int $r, int $c, array $dir): bool
{
    // $grid[$r][$c] = 'X';

    $nR = $r + $dir[0];
    $nC = $c + $dir[1];

    if ($nR < 0 || $nR >= sizeof($grid))
        return true;

    if ($nC < 0 || $nC >= strlen($grid[0]))
        return true;

    // if we hit an obsticle just change dir and recall
    if ($grid[$nR][$nC] == '#') {
        $dir = DIR_MAP[json_encode($dir)];
        $nR = $r;
        $nC = $c;
    }
    // we don't want to replace an existing obsticle
    else {
        global $sR, $sC;
        $prev = $grid[$nR][$nC];
        $grid[$nR][$nC] = 'T';

        $loop = check_loop($grid, $sR, $sC, [-1, 0], []);
        if ($loop) {
            $grid[$nR][$nC] = 'O';
        } else {
            $grid[$nR][$nC] = $prev;
        }
    }

    if (walk($grid, $nR, $nC, $dir) === true)
        return true;

    return false;
}

walk($grid, $sR, $sC, [-1, 0]);

echo implode("\n", $grid) . "\n";

$res = 0;

for ($r = 0; $r < sizeof($grid); $r++)
    for ($c = 0; $c < strlen($grid[0]); $c++)
        if ($grid[$r][$c] == 'O')
            $res++;

echo $res . "\n";
