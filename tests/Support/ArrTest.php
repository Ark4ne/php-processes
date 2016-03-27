<?php
/**
 * Created by PhpStorm.
 * User: guial
 * Date: 27/03/2016
 * Time: 20:51
 */

namespace Support;


use Ark4ne\Support\Arr;

/**
 * Class ArrTest
 *
 * @package Support
 */
class ArrTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataArrToString()
    {
        return [
            ['--1', [1], '', '--'],
            ['1 2 3', [1, 2, 3]],
            ['1 2 3', [1, 2, 3], ' '],
            ['1 2 3', [1, 2, 3], ' ', ''],
            ['1 2 3', [1, 2, 3], ' ', '', ''],
            ['1,2,3', [1, 2, 3], ','],
            ['-1,2,3', [1, 2, 3], ',', '-'],
            ['--1,2,3-', [1, 2, 3], ',', '--', '-'],
        ];
    }

    /**
     * @test
     * @dataProvider dataArrToString
     *
     * @param string $expected
     * @param array  $array
     * @param string $separator
     * @param string $prefix
     * @param string $suffix
     */
    public function testToString($expected, array $array, $separator = ' ', $prefix = '', $suffix = '')
    {
        // WHEN
        $actual = Arr::toString($array, $separator, $prefix, $suffix);

        //THEN
        $this->assertEquals($expected, $actual);
    }

}
