<?php
/**
 * Created by PhpStorm.
 * User: guial
 * Date: 27/03/2016
 * Time: 21:04
 */

namespace Support;


use Ark4ne\Support\Str;

/**
 * Class StrTest
 *
 * @package Support
 */
class StrTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataStrFromVar()
    {
        return [
            ['', null],
            ['0', false],
            ['1', true],
            ['0', 0],
            ['1', 1],
            ['1', '1'],
            ['abc.xyz', 'abc.xyz'],
            ['[1,2,3]', [1, 2, 3]],
            ['[1,"2","3"]', [1, '2', "3"]],
            ['Stringable : 1', new StrTesting__toString(1)],
            ['C:28:"Support\StrTestingSerialized":7:{#val:1#}', new StrTestingSerialized(1)],
            ['{"val":1}', (object)['val' => 1]],
        ];
    }

    /**
     * @test
     * @dataProvider dataStrFromVar
     *
     * @param string $expected
     * @param mixed  $var
     */
    public function testFromVar($expected, $var)
    {
        // WHEN
        $actual = Str::fromVar($var);

        //THEN
        $this->assertEquals($expected, $actual);
    }

}

/**
 * Class StrTesting for testing
 *
 * @package Support
 */
class StrTesting
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * StrTestingSerialized constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}

/**
 * Class StrTesting__toString for testing Str::fromVar case 'method_exist __toString'
 *
 * @package Support
 */
class StrTesting__toString extends StrTesting
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)'Stringable : ' . $this->value;
    }
}

/**
 * Class StrTestingSerialized for testing Str::fromVar case 'instanceof Serializable'
 *
 * @package Support
 */
class StrTestingSerialized extends StrTesting implements \Serializable
{
    /**
     * String representation of object
     *
     * @return string
     */
    public function serialize()
    {
        return '#val:' . $this->value . '#';
    }

    /**
     * Constructs the object
     *
     * @param string $serialized
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        preg_match('/^#val:(.*)#$/', $serialized, $value);

        if (count($value) === 2) {
            $this->value = $value[1];
        }
    }
}