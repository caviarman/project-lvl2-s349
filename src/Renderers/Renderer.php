<?php

namespace App\Renderer;

use function Funct\Collection\flattenAll;

function render($ast, $format)
{
    $formats = [
        'pretty' => function ($ast) {
            return pretty($ast);
        },
        'plain' => function ($ast) {
            return plain($ast);
        },
        'json' => function ($ast) {
            return json_encode($ast, JSON_PRETTY_PRINT);
        }
    ];
    return $formats[$format]($ast);
}
