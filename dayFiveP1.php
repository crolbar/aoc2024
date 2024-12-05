<?php
/**
 * @var string $input
 */

$input = trim($input);

// key has to be before than values
$rules_map = [];

[$rules, $updates] = explode("\n\n",  $input);

$rules = explode("\n", $rules);
$updates = explode("\n", $updates);

foreach ($rules as $rule)
{
    [$key, $value] = explode("|", $rule);

    $rules_map[$key][] = $value;
}


function validate_rules($prev_numbers, $rules_map, $page_number): bool
{
    if (!array_key_exists($page_number, $rules_map))
        return false;

    foreach ($prev_numbers as $prev_number)
    {
        if (in_array($prev_number, $rules_map[$page_number]))
            return true;
    }

    return false;
}


$sum = 0;

foreach ($updates as $update)
{
    $page_numbers = explode(",", $update);
    $prev_numbers = [];

    foreach ($page_numbers as $page_number)
    {
        if (validate_rules($prev_numbers, $rules_map, $page_number))
            continue 2;

        $prev_numbers[] = $page_number;
    }

    $sum += $page_numbers[sizeof($page_numbers) / 2];
}

echo $sum;
