<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

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
