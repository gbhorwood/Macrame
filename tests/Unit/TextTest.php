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
#[CoversClass(\Gbhorwood\Macrame\MacrameText::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameIO::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameText::class)]
class TextTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Test text()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextWrite()
    {
        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($testText);
        $cli->write();
        ob_clean();

        $this->expectOutputString($testText);
        $cli->wrap()->write();
    }

    /**
     * Test text()->get()
     *
     * @runInSeparateProcess
     */
    public function testTextGet()
    {
        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->get(), $testText);
    }

    /**
     * Test text()->text()
     *
     * @runInSeparateProcess
     */
    public function testTextText()
    {
        /**
         * Data
         */
        $originalText = 'original';
        $replacementText = 'replacement';

        $cli = new \Gbhorwood\Macrame\MacrameText($originalText);

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->text($replacementText)->get(), $replacementText);
    }

    /**
     * Test text()->append()
     *
     * @runInSeparateProcess
     */
    public function testTextAppend()
    {
        /**
         * Data
         */
        $originalText = 'original';
        $appendText = 'appended';

        $cli = new \Gbhorwood\Macrame\MacrameText($originalText);

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->append($appendText)->get(), $originalText.$appendText);
    }

    /**
     * Test text()->writeError()
     *
     * @runInSeparateProcess
     */
    public function testTextWriteError()
    {
        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->expectOutputString($testText);
        $cli->text($testText)->writeError();
    }

    /**
     * Test text()->get()
     * Handle null text in format()
     *
     * @runInSeparateProcess
     */
    public function testTextNull()
    {
        $cli = new \Gbhorwood\Macrame\MacrameText();

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->get(), null);

        $this->expectOutputString('');
        $cli->write();
    }

    /**
     * Test text()->colour()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextColourWrite()
    {

        /**
         * Data
         */
        $testText = 'some text';

        $blackText = "\033[30m".$testText."\033[0m";
        $redText = "\033[31m".$testText."\033[0m";
        $greenText = "\033[32m".$testText."\033[0m";
        $yellowText = "\033[33m".$testText."\033[0m";
        $blueText = "\033[34m".$testText."\033[0m";
        $magentaText = "\033[35m".$testText."\033[0m";
        $cyanText = "\033[36m".$testText."\033[0m";
        $whiteText = "\033[37m".$testText."\033[0m";

        /**
         * Tests and assertions
         */
        $this->expectOutputString($blackText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('black')->write();
        ob_clean();

        $this->expectOutputString($blackText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->color('black')->write(); // americans exist
        ob_clean();

        $this->expectOutputString($redText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('red')->write();
        ob_clean();

        $this->expectOutputString($greenText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('green')->write();
        ob_clean();

        $this->expectOutputString($yellowText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('yellow')->write();
        ob_clean();

        $this->expectOutputString($blueText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('blue')->write();
        ob_clean();

        $this->expectOutputString($magentaText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('magenta')->write();
        ob_clean();

        $this->expectOutputString($cyanText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('cyan')->write();
        ob_clean();

        $this->expectOutputString($whiteText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->colour('white')->write();
    }

    /**
     * Test text()->colour()->colour()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextColourColourWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        $whiteThenRedText = "\033[37m\033[31m".$testText."\033[0m";

        /**
         * Tests and assertions
         */
        $this->expectOutputString($whiteThenRedText);
        $cli->colour('white')->colour('red')->write();
    }


    /**
     * Test text()->backgroundColour()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextBackgroundColourWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $blackText = "\033[40m".$testText."\033[0m";
        $redText = "\033[41m".$testText."\033[0m";
        $greenText = "\033[42m".$testText."\033[0m";
        $yellowText = "\033[43m".$testText."\033[0m";
        $blueText = "\033[44m".$testText."\033[0m";
        $magentaText = "\033[45m".$testText."\033[0m";
        $cyanText = "\033[46m".$testText."\033[0m";
        $whiteText = "\033[47m".$testText."\033[0m";

        /**
         * Tests and assertions
         */
        $this->expectOutputString($blackText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('black')->write();
        ob_clean();

        $this->expectOutputString($blackText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColor('black')->write(); // americans exist
        ob_clean();

        $this->expectOutputString($redText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('red')->write();
        ob_clean();

        $this->expectOutputString($greenText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('green')->write();
        ob_clean();

        $this->expectOutputString($yellowText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('yellow')->write();
        ob_clean();

        $this->expectOutputString($blueText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('blue')->write();
        ob_clean();

        $this->expectOutputString($magentaText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('magenta')->write();
        ob_clean();

        $this->expectOutputString($cyanText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('cyan')->write();
        ob_clean();

        $this->expectOutputString($whiteText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->backgroundColour('white')->write();
    }

    /**
     * Test text()->style()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextStyleWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $boldText = "\033[1m".$testText."\033[0m";
        $italicText = "\033[3m".$testText."\033[0m";
        $underlineText = "\033[4m".$testText."\033[0m";
        $strikeText = "\033[9m".$testText."\033[0m";
        $boldItalicText = "\033[1m\033[3m".$testText."\033[0m";

        /**
         * Tests and assertions
         */
        $this->expectOutputString($boldText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->style('bold')->write();
        ob_clean();

        $this->expectOutputString($italicText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->text($testText)->style('italic')->write();
        ob_clean();

        $this->expectOutputString($underlineText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->style('underline')->write();
        ob_clean();

        $this->expectOutputString($strikeText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->style('strike')->write();
        ob_clean();

        $this->expectOutputString($strikeText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->style('strikethrough')->write();
        ob_clean();

        $this->expectOutputString($boldItalicText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->style('bold', 'italic')->write();
    }

    /**
     * Test text()->right()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextRightWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->right()->write();
    }

    /**
     * Test text()->centre()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextCentreWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->centre()->write();
        ob_clean();

        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->text($testText)->center()->write();
    }

    /**
     * Test text()->left()->write()
     *
     * @runInSeparateProcess
     */
    public function testTextLeftWrite()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $cli = new \Gbhorwood\Macrame\MacrameText($testText);

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^'.$testText.'$/');
        $cli->left()->write();
    }

    /**
     *
     * @runInSeparateProcess
     */
    public function testTextLevels()
    {
        /**
         * Data
         */
        $testText = 'some text';

        $okText = '['."\033[32m"."\033[1m".'OK'."\033[0m".']'.' '.$testText.PHP_EOL;
        $debugText = '['."\033[32m"."\033[1m".'DEBUG'."\033[0m".']'.' '.$testText.PHP_EOL;
        $infoText = '['."\033[32m"."\033[1m".'INFO'."\033[0m".']'.' '.$testText.PHP_EOL;
        $noticeText = '['."\033[32m"."\033[1m".'NOTICE'."\033[0m".']'.' '.$testText.PHP_EOL;
        $warningText = '['."\033[33m"."\033[1m".'WARNING'."\033[0m".']'.' '.$testText.PHP_EOL;
        $errorText = '['."\033[31m"."\033[1m".'ERROR'."\033[0m".']'.' '.$testText.PHP_EOL;
        $alertText = '['."\033[33m"."\033[1m".'ALERT'."\033[0m".']'.' '.$testText.PHP_EOL;
        $criticalText = '['."\033[31m"."\033[1m".'CRITICAL'."\033[0m".']'.' '.$testText.PHP_EOL;
        $emergencyText = '['."\033[31m"."\033[1m".'EMERGENCY'."\033[0m".']'.' '.$testText.PHP_EOL;

        /**
         * Tests and assertions
         */
        $this->expectOutputString($okText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->ok();
        ob_clean();

        $this->expectOutputString($debugText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->debug();
        ob_clean();

        $this->expectOutputString($infoText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->info();
        ob_clean();

        $this->expectOutputString($noticeText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->notice();
        ob_clean();

        $this->expectOutputString($warningText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->warning();
        ob_clean();

        $this->expectOutputString($alertText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->alert();
        ob_clean();

        $this->expectOutputString($errorText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->error();
        ob_clean();

        $this->expectOutputString($criticalText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->critical();
        ob_clean();

        $this->expectOutputString($emergencyText);
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $cli->emergency();
    }

    /**
     * applyAnsiWrapper() plain text
     *
     * @runInSeparateProcess
     */
    public function testApplyAnsiWrapperPlain()
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');

        /**
         * Data
         */
        $testText = <<<TXT
        this is the test text for testing wrapping that is ANSI safe. we are testing this on thirty cols. this line ends on on.

        New paragraph.


        New paragraph after two PHP_EOL.
        TXT;

        $expectsText = <<<TXT
        this is the test text for
        testing wrapping that is ANSI
        safe. we are testing this on
        thirty cols. this line ends on
        on.

        New paragraph.


        New paragraph after two
        PHP_EOL.
        TXT;

        /**
         * Test
         */
        $result = $cliText->applyAnsiWrapper($testText, 30);

        /**
         * Assertions
         */
        $this->assertEquals($result, $expectsText);
    }

    /**
     * applyAnsiWrapper() ANSI-styled text
     *
     * @runInSeparateProcess
     */
    public function testApplyAnsiWrapperStyled()
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');

        /**
         * Data
         */
        $testText = <<<TXT
        this is the \033[31m\033[1m\033[3mtest\033[0m text for testing wrapping that is ANSI safe. we are testing this on thirty cols. this line ends on on.

        New paragraph.


        New paragraph after two PHP_EOL.
        TXT;

        $expectsText = <<<TXT
        this is the \033[31m\033[1m\033[3mtest\033[0m text for
        testing wrapping that is ANSI
        safe. we are testing this on
        thirty cols. this line ends on
        on.

        New paragraph.


        New paragraph after two
        PHP_EOL.
        TXT;

        /**
         * Test
         */
        $result = $cliText->applyAnsiWrapper($testText, 30);

        /**
         * Assertions
         */
        $this->assertEquals($result, $expectsText);
    }

    /**
     * Test page()
     * <CR>
     *
     * @runInSeparateProcess
     */
    public function testPageOneLine()
    {
        /**
         * data
         */
        $testText = '';
        for ($i = 0;$i < 100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }
        $keydowns = array_fill(0, 200, chr(10));

        /**
         * Mocks
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame', "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keydowns);

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $this->expectOutputRegex("/^line 0/i");
        $cli->page();
    }

    /**
     * Test page()
     * <SPACE>
     *
     * @runInSeparateProcess
     */
    public function testPageOnePage()
    {
        /**
         * Data
         */
        $testText = '';
        for ($i = 0;$i < 100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }
        $keydowns = array_fill(0, 200, ' ');


        /**
         * Mocks
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame', "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keydowns);

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $this->expectOutputRegex("/^line 0/i");
        $cli->page();
    }

    /**
     * Test page()
     * <q>
     *
     * @runInSeparateProcess
     */
    public function testPageQuit()
    {
        /**
         * Data
         */
        $testText = '';
        for ($i = 0;$i < 100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }

        /**
         * Mocks
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame', "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls('q');

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $this->expectOutputRegex("/^line 0/i");
        $cli->page();
    }

    /**
     * Test rowCount()
     *
     * @runInSeparateProcess
     */
    public function testRowCount()
    {
        /**
         * Data
         */
        $testText = <<<TXT
        one
        two
        three
        TXT;

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $result = $cli->rowCount();
        $this->assertEquals(3, $result);
    }

    /**
     * Test reverse()
     *
     * @runInSeparateProcess
     */
    public function testReverse()
    {
        /**
         * Data
         */
        $testText = "some text";
        $expected = "\033[7m".$testText."\033[0m";

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $result = $cli->reverse()->get();
        $this->assertEquals($expected, $result);
    }

    /**
     * mb_strwidth_ansi()
     *
     * @dataProvider strlenProvider
     * @runInSeparateProcess
     */
    public function testStrlenAnsiSafe($string, $length)
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');
        $this->assertEquals($cliText->mb_strwidth_ansi($string), $length);
    }

    /**
     * Test tags
     *
     * @runInSeparateProcess
     */
    public function testTags()
    {
        /**
         * Data
         */
        $testText = "<!REVERSE!>reverse<!CLOSE!> <!RED!>red<!CLOSE!>";
        $expected = "\033[7mreverse\033[0m \033[31mred\033[0m";

        /**
         * Test and assertion
         */
        $cli = new \Gbhorwood\Macrame\MacrameText($testText);
        $result = $cli->get();
        $this->assertEquals($expected, $result);
    }

    /**
     * Provides strings and lengths to test mb_strwidth_ansi()
     *
     * @return Array
     */
    public static function strlenProvider(): array
    {

        $italic = "\033[3m";
        $red = "\033[31m";
        $close = "\033[0m";

        return [
            ["string!", 7],
            ["striñg!", 7],
            ["\ttab", 7],           // tab
            ["仝string", 8],        // utf-8
            ["🌈string", 8],        // emoji
            ["str\x7fing!", 7],     // delete ignored
            ["str\x08iing!", 7],    // backspace not ignored
            ["str🌈🌈\x08\x08ng", 7],

            [$red.$italic."string!".$close, 7],
            [$red.$italic."striñg!".$close, 7],
            [$red.$italic."\ttab".$close, 7],
            [$red.$italic."仝string".$close, 8],
            [$red.$italic."🌈string".$close, 8],
            [$red.$italic."str\x7fing!".$close, 7],
            [$red.$italic."str\x08iing!".$close, 7],
            [$red.$italic."str🌈🌈\x08ng".$close, 8],
        ];
    }
}
