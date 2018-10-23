<?php

namespace App;

use PHPUnit\Framework\TestCase;
use function App\Diff\genDiff;

class DiffTest extends TestCase
{
    public function testJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ . "/fixtures/before.json", __DIR__ . "/fixtures/after.json");
        $this->assertEquals($expected, $actual);
    }
    public function testYaml()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ . "/fixtures/before.yaml", __DIR__ . "/fixtures/after.yaml");
        $this->assertEquals($expected, $actual);
    }
}
