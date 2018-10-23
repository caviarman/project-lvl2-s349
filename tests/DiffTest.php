<?php

namespace App;

use PHPUnit\Framework\TestCase;
use function App\genDiff;

class DiffTest extends TestCase
{
    public function testDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ ."/fixtures/before.json", __DIR__ ."/fixtures/after.json");
        $this->assertEquals($expected, $actual);
    }
}
