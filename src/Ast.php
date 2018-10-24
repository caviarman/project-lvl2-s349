<?php

namespace App\Ast;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}

function getAst($before, $after)
{
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
    return array_map(function ($item) use ($before, $after) {
        $valueBefore = $before[$item] ?? '';
        $valueAfter = $after[$item] ?? '';
        $beforeValue = is_bool($valueBefore) ? boolToStr($valueBefore) : $valueBefore;
        $afterValue = is_bool($valueAfter) ? boolToStr($valueAfter) : $valueAfter;
        if (is_array($beforeValue) && is_array($afterValue)) {
            return getAst($beforeValue, $afterValue);
        }
        if (array_key_exists($item, $before) && array_key_exists($item, $after)) {
            if ($beforeValue === $afterValue) {
                $properties = [
                    'type' => 'unchanged',
                    'key' => $item,
                    'beforeValue' => $beforeValue,
                    'afterValue' => $afterValue
                ];
            } else {
                $properties = [
                    'type' => 'changed',
                    'key' => $item,
                    'beforeValue' => $beforeValue,
                    'afterValue' => $afterValue
                ];
            }
        }
        if (array_key_exists($item, $before) && !array_key_exists($item, $after)) {
            $properties = [
                'type' => 'deleted',
                'key' => $item,
                'beforeValue' => $beforeValue,
            ];
        }
        if (!array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $properties = [
                'type' => 'added',
                'key' => $item,
                'beforeValue' => $beforeValue,
                'afterValue' => $afterValue
            ];
        }
        return $properties;
    }, $keys);
}
