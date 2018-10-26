<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use function App\Diff\genDiff;
use function App\Parser\parse;

class DiffTest extends TestCase
{
    public function testJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ . "/fixtures/before.json", __DIR__ . "/fixtures/after.json", 'pretty');
        $this->assertEquals($expected, $actual);
    }
    public function testYaml()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ . "/fixtures/before.yaml", __DIR__ . "/fixtures/after.yaml", 'pretty');
        $this->assertEquals($expected, $actual);
    }
    public function testInnerJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedInner.txt");
        $actual = genDiff(__DIR__ . "/fixtures/beforeInner.json", __DIR__ . "/fixtures/afterInner.json", 'pretty');
        $this->assertEquals($expected, $actual);
    }
    public function testPlainJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedPlain.txt");
        $actual = genDiff(__DIR__ . "/fixtures/beforeInner.json", __DIR__ . "/fixtures/afterInner.json", 'plain');
        $this->assertEquals($expected, $actual);
    }
}
