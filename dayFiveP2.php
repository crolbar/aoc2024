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

$incorrect = [];

foreach ($updates as $update)
{
    $page_numbers = explode(",", $update);
    $prev_numbers = [];

    foreach ($page_numbers as $page_number)
    {
        if (validate_rules($prev_numbers, $rules_map, $page_number)) {
            $incorrect[] = $update;
            continue 2;
        }

        $prev_numbers[] = $page_number;
    }
}

function r(array $graph, array &$visited, string $curr, array &$res)
{
    if (isset($visited[$curr]) && $visited[$curr] === true)
        return;

    $visited[$curr] = true;

    foreach ($graph[$curr] as $next)
    {
        r($graph, $visited, $next, $res);
    }

    $res[] = $curr;
}

function construct_update($graph): array
{
    // key page_number - val bool
    $visited = [];
    $res = [];


    foreach ($graph as $key => $val)
    {
        r($graph, $visited, $key, $res);
    }

    return $res;
}

$sum = 0;

foreach ($incorrect as $update)
{
    // basicly values from rules_map but just for this "update"
    $graph = [];

    $page_numbers = explode(",", $update);
    foreach ($page_numbers as $page_number)
    {
        $graph[$page_number] = [];

        if (!array_key_exists($page_number, $rules_map)) {
            continue;
        }

        foreach ($page_numbers as $page_number2)
        {
            if (in_array($page_number2, $rules_map[$page_number]))
                $graph[$page_number][] = $page_number2;
        }
    }

    // its reversed because of post dfs but still works hehe
    $correct_update = construct_update($graph);

    $sum += (int)$correct_update[sizeof($correct_update) / 2];
}

echo $sum . "\n";
