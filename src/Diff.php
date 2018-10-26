<?php

namespace App\Diff;

use function App\Parser\parse;
use function App\Ast\getAst;
use function App\Renderer\render;
use function Funct\Collection\flattenAll;
use function App\Renderer\getPretty;

function boolToStr($bool)
{
    return $bool ? 'true' : 'false';
}
function getExtension($path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
function getData($path)
{
    return file_get_contents($path, true);
}

function genDiff($pathBefore, $pathAfter, $format)
{
    $before = parse(getExtension($pathBefore), getData($pathBefore));
    $after = parse(getExtension($pathAfter), getData($pathAfter));
    $ast = getAst($before, $after);
    return render($ast, $format);
}
