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
        $actual = genDiff(__DIR__ . "/fixtures/before.json", __DIR__ . "/fixtures/after.json");
        $this->assertEquals($expected, $actual);
    }
    public function testYaml()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $actual = genDiff(__DIR__ . "/fixtures/before.yaml", __DIR__ . "/fixtures/after.yaml");
        $this->assertEquals($expected, $actual);
    }
    public function testInnerJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedInner.txt");
        $actual = genDiff(__DIR__ . "/fixtures/beforeInner.json", __DIR__ . "/fixtures/afterInner.json");
        $this->assertEquals($expected, $actual);
    }
    public function testParser()
    {
        $expected = ['common' => [
            'setting1' => 'Value 1',
            'setting2' => '200',
            'setting3' => true,
            'setting6' => ['key' => 'value']
          ],
          'group1' => [
            'baz' => 'bas',
            'foo' => 'bar',
            'nest' => ['key' => 'value']
          ],
          'group2' => ['abc' => '12345']];

        $content = file_get_contents(__DIR__ . "/fixtures/beforeInner.json");
        $actual = parse('json', $content);
        $this->assertEquals($expected, $actual);
    }
}
