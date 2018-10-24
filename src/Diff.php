<?php

namespace App\Diff;

use function App\Parser\parse;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}
function getExtension($path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
function getData($path)
{
    return file_get_contents($path, true);
}

function genDiff($pathBefore, $pathAfter)
{
    $before = parse(getExtension($pathBefore), getData($pathBefore));
    $after = parse(getExtension($pathAfter), getData($pathAfter));
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
    $arrOfLines = array_reduce($keys, function ($acc, $item) use ($before, $after) {
        $valueBefore = $before[$item] ?? '';
        $valueAfter = $after[$item] ?? '';
        $beforeValue = is_bool($valueBefore) ? boolToStr($valueBefore) : $valueBefore;
        $afterValue = is_bool($valueAfter) ? boolToStr($valueAfter) : $valueAfter;
        if (array_key_exists($item, $before) && array_key_exists($item, $after)) {
            if ($beforeValue === $afterValue) {
                $acc = array_merge($acc, ["  $item: $beforeValue"]);
            } else {
                $acc = array_merge($acc, ["- $item: $beforeValue"], ["+ $item: $afterValue"]);
            }
        }
        if (array_key_exists($item, $before) && !array_key_exists($item, $after)) {
            $acc = array_merge($acc, ["- $item: $beforeValue"]);
        }
        if (!array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $acc = array_merge($acc, ["+ $item: $afterValue"]);
        }
        return $acc;
    }, []);
    $str = implode("\n", $arrOfLines);
    return "$str\n";
}
