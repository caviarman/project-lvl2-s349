<?php

namespace App;

use Symfony\Component\Yaml\Yaml;

function parse($path)
{
    $parsers = [
        'json' => function ($data) {
            return json_decode($data, true);
        },
        'yaml' => function ($data) {
            return Yaml::parse($data);
        }
    ];
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return $parsers[$extension]($data);
}
