<?php
/**
 * @var string $input
 */

$input = trim($input);
echo $input . "\n";

$blocks = [];
$curr_block_i = 0;
$curr_file_id = 0;

for ($i = 0; $i < strlen($input); $i++)
{
    $num = (int)$input[$i];

    $blocks[$curr_block_i] = [];
    for ($j = 0; $j < $num; $j++)
    {
        $blocks[$curr_block_i][] = (($i & 1) ? "." : (string)$curr_file_id);
    }
    $curr_block_i++;

    if ($i & 1 > 0)
        $curr_file_id++;
}

// foreach ($blocks as $block)
//     echo implode("", $block) . "|";
// echo "\n";


$next_file = sizeof($blocks) - 1;
while (in_array('.', $blocks[$next_file]))
    $next_file--;

$already_moved_files = [];

while (true)
{
    if ($next_file < 0)
        break;

    // echo "netx: " . json_encode($blocks[$next_file]) . "\n";
    // echo "size: " . sizeof($blocks) . "\n";
    // echo "\n";

    if (isset($already_moved_files[json_encode($blocks[$next_file])])) {
        $next_file -= 2;
        continue;
    }

    $free_space_for_next_file = 1;
    while (
        $free_space_for_next_file < sizeof($blocks) &&
        sizeof($blocks[$free_space_for_next_file]) < sizeof($blocks[$next_file])
    )
        $free_space_for_next_file += 2;

    if ($free_space_for_next_file >= sizeof($blocks)) {
        $next_file -= 2;
        continue;
    }

    if ($free_space_for_next_file >= $next_file) {
        $next_file -= 2;
        continue;
    }


    $already_moved_files[json_encode($blocks[$next_file])] = true;

    $next_file_size  = sizeof($blocks[$next_file]);
    $free_space_size = sizeof($blocks[$free_space_for_next_file]);

    $diff = abs($next_file_size - $free_space_size);

    if ($diff > 0) {
        // remove the free space needed for the file
        for ($i = 0; $i < $next_file_size; $i++) {
            unset($blocks[$free_space_for_next_file][$i]);
            unset($blocks[$free_space_for_next_file][$i]);
        }
        $blocks[$free_space_for_next_file] = array_values($blocks[$free_space_for_next_file]);

        // copy the file before the remaining free space
        array_splice($blocks, $free_space_for_next_file, 0, [$blocks[$next_file]]);

        // put free space before the file so we get an correct array of alternate 'file'|'free space'|'file' ...
        array_splice($blocks, $free_space_for_next_file, 0, [[]]);

        // last replace the file with free space
        $tmp = [];
        $tmp_size = $next_file_size + sizeof($blocks[$next_file + 2 - 1]);

        if ($next_file + 1 + 2 < sizeof($blocks))
            $tmp_size += sizeof($blocks[$next_file + 2 + 1]);

        for ($i = 0; $i < $tmp_size; $i++) {
            $tmp[] = '.';
        }

        array_splice($blocks, $next_file + 2, 1, [$tmp]);

        unset($blocks[$next_file - 1 + 2]);
        unset($blocks[$next_file + 1 + 2]);
        $blocks = array_values($blocks);
    } else {
        // add the free space around the file
        $tmp = $blocks[$free_space_for_next_file];
        $tmp_size = sizeof($blocks[$next_file - 1]);

        if ($next_file + 1 < sizeof($blocks))
            $tmp_size += sizeof($blocks[$next_file + 1]);

        for ($i = 0; $i < $tmp_size; $i++)
            $tmp[] = '.';

        // copy the file at the free space
        $blocks[$free_space_for_next_file] = $blocks[$next_file];

        array_splice($blocks, $free_space_for_next_file + 1, 0, [[]]);
        array_splice($blocks, $free_space_for_next_file, 0, [[]]);

        // put free space at the file
        $blocks[$next_file + 2] = $tmp;

        // remove the free space around the file
        unset($blocks[$next_file - 1 + 2]);
        unset($blocks[$next_file + 1 + 2]);
        $blocks = array_values($blocks);
    }

    // echo "netx: ". $blocks[$next_file] . "\n";


    // foreach ($blocks as $block)
    //     echo implode("", $block) . "|";
    // echo "\n";

    // if ($diff == 0)
        // exit;
}

$res_blocks = [];

foreach ($blocks as $block)
    echo implode("", $block) . "|";
echo "\n";

foreach ($blocks as $block)
    foreach ($block as $num)
        $res_blocks[] = $num;

echo implode("|", $res_blocks) . "\n";

$res = 0;
for ($i = 0; $i < sizeof($res_blocks); $i++)
{
    if ($res_blocks[$i] == ".")
        continue;

    $res += $i * (int)$res_blocks[$i];
}

echo $res . "\n";
