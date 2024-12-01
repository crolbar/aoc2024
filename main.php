<?php

function fetch($url): string
{
    $curl = curl_init();
    $cookie_header = ["Cookie: " . file_get_contents(".cookie")];
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $cookie_header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "curl error: " . curl_error($curl);
    }

    return $res;
}

if (!isset($argv[1]))
{
    exit("no day specified");
}

$day = $argv[1];
$url = "https://adventofcode.com/2024/day/" . $day . "/input";
$input = fetch($url);

$day_word = numfmt_format(
    numfmt_create("en", NumberFormatter::SPELLOUT),
    $day
);


if (!isset($argv[2])) {
    file_put_contents(sprintf("day_%s_input", $day_word), $input);
    exit;
}

$part = $argv[2];
require sprintf("day%sP%s.php", ucfirst($day_word), $part);
