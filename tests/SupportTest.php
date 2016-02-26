<?php

/**
 * Class SupportStrTest
 */
class SupportStrTest extends \PHPUnit_Framework_TestCase
{
    public function testFromVar()
    {
        $datas = [
            [null, null],
            [true, '1'],
            [false, '0'],
            ['str', 'str'],
            [(object)['prop' => 'val'], '{"prop":"val"}'],
        ];
        foreach ($datas as $data) {
            $this->assertEquals($data[1], \Ark4ne\Support\Str::fromVar($data[0]));
        }
    }
}

/**
 * Class SupportArrTest
 */
class SupportArrTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $datas = [
            [[], ''],
            [['1'], '1'],
            [['1', '2'], '1 2']
        ];
        foreach ($datas as $data) {
            $this->assertEquals($data[1], \Ark4ne\Support\Arr::toString($data[0]));
        }
    }
}