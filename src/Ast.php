<?php

namespace App\Ast;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}
function makeNode($type, $key, $before, $after, $children = null)
{
    return [
        'type' => $type,
        'key' => $key,
        'beforeValue' => $before,
        'afterValue' => $after,
        'children' => $children
    ];
}
function getAst($before, $after)
{
    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
    return array_map(function ($item) use ($before, $after) {
        $valueBefore = $before[$item] ?? '';
        $valueAfter = $after[$item] ?? '';
        $beforeValue = is_bool($valueBefore) ? boolToStr($valueBefore) : $valueBefore;
        $afterValue = is_bool($valueAfter) ? boolToStr($valueAfter) : $valueAfter;
        if (array_key_exists($item, $before) && array_key_exists($item, $after)) {
            if (is_array($beforeValue) && is_array($afterValue)) {
                $properties = makeNode('nested',$item, null, null, getAst($beforeValue, $afterValue));
            } elseif ($beforeValue === $afterValue) {
                $properties = makeNode('unchanged', $item, $beforeValue, $afterValue);
            } else {
                $properties = makeNode('changed', $item, $beforeValue, $afterValue);
            }
        }
        if (array_key_exists($item, $before) && !array_key_exists($item, $after)) {
            $properties = makeNode('deleted', $item, $beforeValue, null);
        }
        if (!array_key_exists($item, $before) && array_key_exists($item, $after)) {
            $properties = makeNode('added', $item, null, $afterValue);
        }
        return $properties;
    }, $keys);
}
