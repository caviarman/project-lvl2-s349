<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

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

function pretty($item, $level)
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
                return pretty($item, $level + 1);
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

function plain($item, $path)
{
    [
        'type' => $type,
        'key' =>  $key,
        'beforeValue' => $before,
        'afterValue' => $after,
        'children' => $children
    ] = $item;
    
    $before = is_array($before) ? 'complex value' : $before;
    $after = is_array($after) ? 'complex value' : $after;
    $name = "{$path}{$key}";
    $nameForChildren = "{$path}{$key}.";
    switch ($type) {
        case 'nested':
            return [array_map(function ($item) use ($nameForChildren) {
                return plain($item, $nameForChildren);
            }, $children)];
        
        case 'changed':
            return ["Property '{$name}' was updated. From '{$before}' to '{$after}'"];
            
        case 'deleted':
            return ["Property '{$name}' was removed"];
            
        case 'added':
            return ["Property '{$name}' was added with value: '{$after}'"];
    }
}


function render($ast, $format)
{
    $formats = [
        'pretty' => function ($ast) {
            $arr = array_map(function ($item) {
                return pretty($item, 0);
            }, $ast);
            return implode("\n", flattenAll($arr));
        },
        'plain' => function ($ast) {
            $arr = array_map(function ($item) {
                return plain($item, '');
            }, $ast);
            return implode("\n", array_filter(flattenAll($arr)));
        }
    ];
    return $formats[$format]($ast);
}
