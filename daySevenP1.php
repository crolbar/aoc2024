<?php
/**
 * @var string $input
 */

$input = trim($input);

$targets = [];
$nums = [];


$lines = explode("\n", $input);

foreach ($lines as $line)
{
    [$target, $nums_str] = explode(": ", $line);

    $targets[] = $target;

    $this_nums = [];
    foreach (explode(" ", $nums_str) as $num)
    {
        $this_nums[] = $num;
    }

    $nums[] = $this_nums;
}

echo "targets: " . json_encode($targets) . "\n";
echo "nums: " . json_encode($nums) . "\n";

/**
 * $param array $nums
 * $param int $target
 * $return bool true if possible false otherwise
 **/
function evaluete(
    array $nums,
    int $target,
    int $curr,
    int $i
): bool
{
    if ($curr === $target)
        return true;

    if ($curr > $target)
        return false;

    if ($i >= sizeof($nums))
        return false;

    if (evaluete($nums, $target, $curr + (int)$nums[$i], $i + 1)
        ||evaluete($nums, $target, $curr * (int)$nums[$i], $i + 1))
        return true;

    return false;
}

$res = 0;
for ($i = 0; $i < sizeof($targets); $i++)
{
    if (evaluete($nums[$i], (int)$targets[$i], 0, 0))
        $res += $targets[$i];
}

echo $res . "\n";
