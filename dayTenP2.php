<?php
/**
 * @var string $input
 */

$input = trim($input);

// echo $input;

$grid = explode("\n", $input);

// [
//    [0, 0/*(trailhead)*/] => [[9, 9/*(trail end)*/]],
// ]
$scores = [];

$dirs = [
    [-1, 0], // up
    [1, 0],  // down
    [0, -1], // left
    [0, 1],  // right
];

function dfs(array $grid, array $curr, string $trail_head, array &$scores)
{
    [$r, $c] = $curr;
    if ($grid[$r][$c] == '9') {
        if (!isset($scores[$trail_head]))
            $scores[$trail_head] = [];

            $scores[$trail_head][] = $curr;
        return;
    }

    global $dirs;
    foreach ($dirs as $dir)
    {
        $nR = $r + $dir[0];
        $nC = $c + $dir[1];

        if ($nR < 0 || $nR >= sizeof($grid))
            continue;

        if ($nC < 0 || $nC >= strlen($grid[0]))
            continue;

        $next_needed = ((int)$grid[$r][$c]) + 1;

        if ((int)$grid[$nR][$nC] != $next_needed)
            continue;

        dfs($grid, [$nR, $nC], $trail_head, $scores);
    }
}

for ($r = 0; $r < sizeof($grid); $r++) {
    for ($c = 0; $c < strlen($grid[0]); $c++) {
        if ($grid[$r][$c] == '0')
            dfs($grid, [$r, $c], json_encode([$r, $c]), $scores);
    }
}

$res = 0;
foreach ($scores as $k => $v) {
    echo $k . " => " . json_encode($v) . "\n";
    $res += sizeof($v);
}

echo $res . "\n";
