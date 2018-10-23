<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($extension, $data)
{
    $parsers = [
        'json' => function ($data) {
            return json_decode($data, true);
        },
        'yaml' => function ($data) {
            return Yaml::parse($data);
        }
    ];
    return $parsers[$extension]($data);
}
