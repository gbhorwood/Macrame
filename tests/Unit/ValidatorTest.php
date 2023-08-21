<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Gbhorwood\Macrame\MacrameValidator::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameValidator::class)]
class ValidatorTest extends TestCase
{
    /**
     * Test functionIsNumber()
     *
     * @dataProvider isNumberProvider
     */
    public function testFunctionIsNumber($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsNumber($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsInt()
     *
     * @dataProvider isIntProvider
     */
    public function testFunctionIsInt($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsInt($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsEmail()
     *
     * @dataProvider isEmailProvider
     */
    public function testFunctionIsEmail($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsEmail($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsUrl()
     *
     * @dataProvider isUrlProvider
     */
    public function testFunctionIsUrl($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsUrl($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsIpAddress()
     *
     * @dataProvider isIpAddressProvider
     */
    public function testFunctionIsIpAddress($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsIpAddress($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsDate()
     *
     * @dataProvider isDateProvider
     */
    public function testFunctionIsDate($value, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsDate($error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsLenghtMin()
     *
     * @dataProvider isLengthMinProvider
     */
    public function testFunctionIsLenghtMin($value, $test, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsLengthMin($test, $error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsLenghtMax()
     *
     * @dataProvider isLengthMaxProvider
     */
    public function testFunctionIsLenghtMax($value, $test, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsLengthMax($test, $error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsEqualTo()
     *
     * @dataProvider isEqualToProvider
     */
    public function testFunctionIsEqualTo($value, $test, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsEqualTo($test, $error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsOneOf()
     *
     * @dataProvider isOneOfProvider
     */
    public function testFunctionIsOneOf($value, $testArray, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsOneOf($testArray, $error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Test functionIsPregMatch()
     *
     * @dataProvider isPregMatchProvider
     */
    public function testFunctionIsPregMatch($value, $testExpression, $passes)
    {
        $validator = new \Gbhorwood\Macrame\MacrameValidator();
        $error = 'error '.uniqid();
        $testFunction = $validator->functionIsPregMatch($testExpression, $error);

        if(!$passes) {
            $this->expectOutputRegex("/$error/");
        }

        $this->assertEquals($passes, $testFunction($value));
    }

    /**
     * Provide isNumber test cases
     *
     * @return Array
     */
    public static function isNumberProvider():Array
    {
        return [
            [ '1.0', true ],
            [ '9', true ],
            [ '-9', true ],
            [ '7.86543', true ],
            [ '0', true ],
            [ '0.0', true ],
            [ '009', true ],
            [ 'notanumber', false ],
            [ '1.f', false ],
            [ '0x12', false ],
            [ ' ', false ],
            [ '', false ],
        ];
    }

    /**
     * Provide isInt test cases
     *
     * @return Array
     */
    public static function isIntProvider():Array
    {
        return [
            [ '9', true ],
            [ '0', true ],
            [ '-0', true ],
            [ '-90', true ],
            [ '+90', true ],
            [ '-900009', true ],
            [ 'notanumber', false ],
            [ ' ', false ],
            [ '', false ],
        ];
    }

    /**
     * Provide isEmail test cases
     *
     * @return Array
     */
    public static function isEmailProvider():Array
    {
        return [
            [ 'simple@example.ca', true ],
            [ 'very.camon@example.ca', true ],
            [ 'abc@example.co.uk', true ],
            [ 'disposable.style.email.with+symbol@example.ca', true ],
            [ 'other.email-with-hyphen@example.ca', true ],
            [ 'fully-qualified-domain@example.ca', true ],
            [ 'user.name+tag+sorting@example.ca', true ],
            [ 'example-indeed@strange-example.ca', true ],
            [ 'example-indeed@strange-example.inininini', true ],
            [ '-simple@example.ca', true ],
            [ '_simple@example.ca', true ],
            [ '+simple@example.ca', true ],
            [ 'notanemail', false ],
            [ 'foo @ example . ca', false ],
        ];
    }

    /**
     * Provide isUrl test cases
     *
     * @return Array
     */
    public static function isUrlProvider():Array
    {
        return [
            [ 'https://example.ca', true ],
            [ 'https://example.ca/some/path', true ],
            [ 'https://example.ca/some/path?foo=bar', true ],
            [ 'http://example.ca', true ],
            [ 'http://example.ca/some/path', true ],
            [ 'http://example.ca/some/path?foo=bar', true ],
            [ 'example.ca', false ],
            [ 'example.ca/some/path', false ],
            [ 'example.ca/some/path?foo=bar', false ],
            [ 'notanurl', false ],
        ];
    }

    /**
     * Provide isIpAddress test cases
     *
     * @return Array
     */
    public static function isIpAddressProvider():Array
    {
        return [
            [ '192.168.1.1', true ],
            [ '8.8.8.8', true ],
            [ '2001:db8:3333:4444:5555:6666:7777:8888', true ],
            [ '2001:db8::', true ],
            [ '2001:db8:1::ab9:C0A8:102', true ],
            [ '08.8.8.8', false ],
            [ ':::', false ],
            [ 'notanipaddress', false ],
        ];
    }

    /**
     * Provide isDate test cases
     *
     * @return Array
     */
    public static function isDateProvider():Array
    {
        return [
            [ 'Dec 12th, 1992', true ],
            [ 'December 12th, 1992', true ],
            [ 'Saturday, December 12th, 1992', true ],
            [ 'December 12nd, 1992', true ],
            [ '1992-12-12', true ],
            [ '1992-12-32', false ],
            [ '1992-14-12', false ],
            [ 'notadate', false ],

        ];
    }

    /**
     * Provide isLengthMin test cases
     *
     * @return Array
     */
    public static function isLengthMinProvider():Array
    {
        return [
            ['12345', 4, true],
            ['12345', 5, true],
            ['12345', 6, false],
        ];
    }

    /**
     * Provide isLengthMax test cases
     *
     * @return Array
     */
    public static function isLengthMaxProvider():Array
    {
        return [
            ['12345', 6, true],
            ['12345', 5, true],
            ['12345', 4, false],
        ];
    }

    /**
     * Provide isEqualTo test cases
     *
     * @return Array
     */
    public static function isEqualToProvider():Array
    {
        return [
            ['a string', 'a string', true],
            [' a string', 'a string', true],
            ['a string ', 'a string', true],
            ['a string'.PHP_EOL, 'a string', true],
            ['a string'."\t", 'a string', true],
            ['这是一个测试', '这是一个测试', true],
            ['🚨🚨🚨string', '🚨🚨🚨string', true],
            ['A string ', 'a string', false],
            ['a string ', 'a String', false],
            ['a string ', 'astring', false],
        ];
    }

    /**
     * Provide isOneOf test cases
     *
     * @return Array
     */
    public static function isOneOfProvider():Array
    {
        return [
            ['yes', ['yes', 'no'], true],
            ['yes', ['yes', 'no', 'maybe'], true],
            ['yes', ['yes', 'yes', 'yes'], true],
            ['', ['yes', '', 'no'], true],
            ["🚨", ['one', 'two', "🚨"], true],
            ['four', ['one', 'two', 'three'], false],
            ['TWO', ['one', 'two', 'three'], false],
        ];
    }

    /**
     * Provide isPregMatch test cases
     *
     * @return Array
     */
    public static function isPregMatchProvider():Array
    {
        return [
            ['$19.78', "/\\\$?((\d{1,3}(,\d{3})*)|(\d+))(\.\d{2})?$/", true ],
            ['$09.78', "/\\\$?((\d{1,3}(,\d{3})*)|(\d+))(\.\d{2})?$/", true ],
            ['not dollars', "/\\\$?((\d{1,3}(,\d{3})*)|(\d+))(\.\d{2})?$/", false ],
        ];
    }
}
