<?php

namespace App\Diff;

use function App\Parser\parse;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}

function genDiff($pathBefore, $pathAfter)
{
    $extBefore = pathinfo($pathBefore, PATHINFO_EXTENSION);
    $extAfter = pathinfo($pathAfter, PATHINFO_EXTENSION);
    $dataBefore = file_get_contents($pathBefore);
    $dataAfter = file_get_contents($pathAfter);
    $before = parse($extBefore, $dataBefore);
    $after = parse($extAfter, $dataAfter);
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
    $arrOfLines = array_reduce($keys, function ($acc, $item) use ($before, $after) {
        $valueBefore = $before[$item] ?? '';
        $valueAfter = $after[$item] ?? '';
        $beforeValue = is_bool($valueBefore) ? boolToStr($valueBefore) : $valueBefore;
        $afterValue = is_bool($valueAfter) ? boolToStr($valueAfter) : $valueAfter;
        if (array_key_exists($item, $before) && array_key_exists($item, $after)) {
            if ($beforeValue === $afterValue) {
                $acc = array_merge($acc, array("  $item: $beforeValue"));
            } else {
                $acc = array_merge($acc, array("- $item: $beforeValue"), array("+ $item: $afterValue"));
            }
        }
        if (array_key_exists($item, $before) && !array_key_exists($item, $after)) {
            $acc = array_merge($acc, array("- $item: $beforeValue"));
        }
        if (!array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $acc = array_merge($acc, array("+ $item: $afterValue"));
        }
        return $acc;
    }, []);
    $str = implode("\n", $arrOfLines);
    return "$str\n";
}
