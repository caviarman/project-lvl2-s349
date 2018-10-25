<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

function render($ast)
{
    return array_map(function ($item) {
        [
            'type' => $type,
            'key' =>  $key,
            'beforeValue' => $before,
            'afterValue' => $after,
            'children' => $children
        ] = $item;
    
        switch ($type) {
            case 'nested':
                return [ "$key:", array_map(function ($item) {
                    return render($item);
                }, $children)];
                break;
            case 'unchanged':
                return ["  $key: $before"];
                break;
            case 'changed':
                return ["- $key: $before", "+ $key: $after"];
                break;
            case 'deleted':
                return ["- $key: $before"];
                break;
            case 'added':
                return ["+ $key: $after"];
                break;
        }
    }, $ast);
}
