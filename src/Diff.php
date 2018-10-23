<?php

namespace App;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}

function genDiff($path1, $path2)
{
    $before = json_decode(file_get_contents($path1), true);
    $after = json_decode(file_get_contents($path2), true);
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
    $arrOfLines = array_reduce($keys, function ($acc, $item) use ($before, $after) {
        if (array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $beforeValue = is_bool($before[$item]) ? boolToStr($before[$item]) : $before[$item];
            $afterValue = is_bool($after[$item]) ? boolToStr($after[$item]) : $after[$item];
            if ($beforeValue === $afterValue) {
                return array_merge($acc, array("  $item: $beforeValue"));
            } else {
                return array_merge($acc, array("- $item: $beforeValue"), array("+ $item: $afterValue"));
            }
        }
        if (array_key_exists($item, $before) && !array_key_exists($item, $after)) {
            $beforeValue = is_bool($before[$item]) ? boolToStr($before[$item]) : $before[$item];
            return array_merge($acc, array("- $item: $beforeValue"));
        }
        if (!array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $afterValue = is_bool($after[$item]) ? boolToStr($after[$item]) : $after[$item];
            return array_merge($acc, array("+ $item: $afterValue"));
        }
        return $acc;
    }, []);
    return implode("\n", $arrOfLines);
}
