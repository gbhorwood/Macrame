<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

define('BACKSPACE', '');

/**
 * Tell phpunit when using processIsolation what STDIN is
 */
if (!defined('STDIN')) {
    define('STDIN', fopen("php://stdin", "r"));
}

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameSpinner::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameIO::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameSpinner::class)]
class SpinnerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Test basic
     * spinner()
     */
    public function testSpinner()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test animation
     * spinner()
     */
    public function testSpinnerAnimation()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner('dots 1');

        $result = $spinner->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test color
     * spinner()
     */
    public function testSpinnerColor()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->color('red')->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test backgroundcolor
     * spinner()
     */
    public function testSpinnerBackgroundColor()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->backgroundColor('red')->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test prompt
     * spinner()
     */
    public function testSpinnerPrompt()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->prompt('prompt')->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test speed
     * spinner()
     */
    public function testSpinnerSpeed()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->speed('very fast')->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }

    /**
     * Test style
     * spinner()
     */
    public function testSpinnerStyle()
    {

        $func = function () {
            usleep(300000);
            return "returned results";
        };

        /**
         * test
         */
        $spinner = new \Gbhorwood\Macrame\MacrameSpinner();

        $result = $spinner->style('bold')->run($func);

        /**
         * assertions
         */
        $this->assertEquals($result, "returned results");
    }
}
