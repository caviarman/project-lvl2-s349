<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

function pretty($ast)
{
    $arr = array_map(function ($item) {
        return getPretty($item, 0);
    }, $ast);
    return implode("\n", array_filter(flattenAll($arr)));
}

function getPretty($item, $level)
{
    [
        'type' => $type,
        'key' =>  $key,
        'beforeValue' => $before,
        'afterValue' => $after,
        'children' => $children
    ] = $item;

    $before = normalize($before, $level);
    $after = normalize($after, $level);

    switch ($type) {
        case 'nested':
            return [getSpace($level) . "  $key: {", array_map(function ($item) use ($level) {
                return getPretty($item, $level + 1);
            }, $children), getSpace($level) . "  }"];
            
        case 'unchanged':
            return [getSpace($level) . "  $key: $before"];
            
        case 'changed':
            return [getSpace($level) . "- $key: $before", getSpace($level) . "+ $key: $after"];
            
        case 'deleted':
            return [getSpace($level) . "- $key: $before"];
            
        case 'added':
            return [getSpace($level) . "+ $key: $after"];
    }
}

function getSpace($level)
{
    return str_repeat(' ', $level * 4);
}

function normalize($value, $level)
{
    if (!is_array($value)) {
        return $value;
    } else {
        $keys = array_keys($value);
        $arr = array_map(function ($item) use ($value, $level) {
            return [ PHP_EOL . getSpace($level + 1) . "$item: $value[$item]"];
        }, $keys);
    }
    return implode("", flattenAll(array_merge(["{"], $arr, [PHP_EOL . getSpace($level) . "  }"])));
}
