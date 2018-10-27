<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

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
