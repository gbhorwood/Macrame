<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Tell phpunit when using processIsolation what STDIN is
 */
if (!defined('STDIN')) {
    define('STDIN', fopen("php://stdin", "r"));
}

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameFiglet::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameFiglet::class)]
class FigletTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Test figlet()->write()
     *
     * @runInSeparateProcess
     */
    public function testFigletWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = '   __                 '.PHP_EOL.
                            '  / _|                '.PHP_EOL.
                            ' | |_    ___     ___  '.PHP_EOL.
                            ' |  _|  / _ \   / _ \ '.PHP_EOL.
                            ' | |   | (_) | | (_) |'.PHP_EOL.
                            ' |_|    \___/   \___/ '.PHP_EOL;


        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->write();
    }

    /**
     * Test figlet()->write()
     * colour
     *
     * @runInSeparateProcess
     */
    public function testFigletColourWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = "\033[31m   __                 ".PHP_EOL.
                            "  / _|                ".PHP_EOL.
                            " | |_    ___     ___  ".PHP_EOL.
                            " |  _|  / _ \   / _ \ ".PHP_EOL.
                            " | |   | (_) | | (_) |".PHP_EOL.
                            " |_|    \___/   \___/ \033[0m".PHP_EOL;


        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->colour('red')->write();
    }

    /**
     * Test figlet()->write()
     * color
     *
     * @runInSeparateProcess
     */
    public function testFigletColorWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = "\033[31m   __                 ".PHP_EOL.
                            "  / _|                ".PHP_EOL.
                            " | |_    ___     ___  ".PHP_EOL.
                            " |  _|  / _ \   / _ \ ".PHP_EOL.
                            " | |   | (_) | | (_) |".PHP_EOL.
                            " |_|    \___/   \___/ \033[0m".PHP_EOL;


        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->color('red')->write();
    }

    /**
     * Test figlet()->write()
     * bold
     *
     * @runInSeparateProcess
     */
    public function testFigletBoldWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = "\033[1m   __                 ".PHP_EOL.
                            "  / _|                ".PHP_EOL.
                            " | |_    ___     ___  ".PHP_EOL.
                            " |  _|  / _ \   / _ \ ".PHP_EOL.
                            " | |   | (_) | | (_) |".PHP_EOL.
                            " |_|    \___/   \___/ \033[0m".PHP_EOL;


        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->bold()->write();
    }

    /**
     * Test figlet()->write()
     * font
     *
     * @runInSeparateProcess
     */
    public function testFigletFontWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = "     _|_|                        ".PHP_EOL.
                            "   _|         _|_|       _|_|    ".PHP_EOL.
                            " _|_|_|_|   _|    _|   _|    _|  ".PHP_EOL.
                            "   _|       _|    _|   _|    _|  ".PHP_EOL.
                            "   _|         _|_|       _|_|    ".PHP_EOL;

        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->font('block')->write();
    }

    /**
     * Test figlet()->write()
     * font invalid
     *
     * @runInSeparateProcess
     */
    public function testFigletFontInvalidWrite()
    {
        /**
         * Data
         */
        $testHeadline = 'foo';
        $expectedHeadline = "[\033[33m\033[1mWARNING\033[0m] 'notarealfont' is not a valid font".PHP_EOL.
                            '   __                 '.PHP_EOL.
                            '  / _|                '.PHP_EOL.
                            ' | |_    ___     ___  '.PHP_EOL.
                            ' |  _|  / _ \   / _ \ '.PHP_EOL.
                            ' | |   | (_) | | (_) |'.PHP_EOL.
                            ' |_|    \___/   \___/ '.PHP_EOL;


        $cli = new \Gbhorwood\Macrame\MacrameFiglet($testHeadline);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($expectedHeadline);
        $cli->font('notarealfont')->write();
    }

    /**
     * Test figlet()->wrap()
     *
     * @runInSeparateProcess
     */
    public function testFigletWrap()
    {
        /**
         * Data
         */
        $headline = 'some head';
        $terminalWidth = 10;

        /**
         * Mock getTerminalWidth()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameFiglet::class)->setConstructorArgs([$headline])->onlyMethods(['getTerminalWidth'])->getMock();
        $mockedMacrame->expects($this->any())->method('getTerminalWidth')->will($this->returnValue($terminalWidth));

        /**
         * Tests and assertions
         */
        $wrapped = $mockedMacrame->wrap();

        $this->assertEquals(count($wrapped), 2);

    }
}
