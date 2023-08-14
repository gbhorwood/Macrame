<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameText::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameText::class)]
class TextTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Test text()->write() 
     *
     */
    public function testTextWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';
        
        /**
         * Tests and assertions
         */
        $this->expectOutputString($testText);
        $cli->text($testText)->write();
        ob_clean();

        $this->expectOutputString($testText);
        $cli->text($testText)->wrap()->write();
    }

    /**
     * Test text()->get() 
     *
     */
    public function testTextGet()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';
        
        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->text($testText)->get(), $testText);
    }

    /**
     * Test text()->text() 
     *
     */
    public function testTextText()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $originalText = 'original';
        $replacementText = 'replacement';

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->text($originalText)->text($replacementText)->get(), $replacementText);
    }

    /**
     * Test text()->append() 
     *
     */
    public function testTextAppend()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $originalText = 'original';
        $appendText = 'appended';

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->text($originalText)->append($appendText)->get(), $originalText.$appendText);
    }

    /**
     * Test text()->writeError() 
     *
     */
    public function testTextWriteError()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text'.PHP_EOL.'new line';
        
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
     */
    public function testTextNull()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Tests and assertions
         */
        $this->assertEquals($cli->text()->get(), null);

        $this->expectOutputString('');
        $cli->text()->write();
    }

    /**
     * Test text()->colour()->write() 
     *
     */
    public function testTextColourWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

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
        $cli->text($testText)->colour('black')->write();
        ob_clean();
        $this->expectOutputString($blackText);
        $cli->text($testText)->color('black')->write(); // americans exist
        ob_clean();

        $this->expectOutputString($redText);
        $cli->text($testText)->colour('red')->write();
        ob_clean();

        $this->expectOutputString($greenText);
        $cli->text($testText)->colour('green')->write();
        ob_clean();

        $this->expectOutputString($yellowText);
        $cli->text($testText)->colour('yellow')->write();
        ob_clean();

        $this->expectOutputString($blueText);
        $cli->text($testText)->colour('blue')->write();
        ob_clean();

        $this->expectOutputString($magentaText);
        $cli->text($testText)->colour('magenta')->write();
        ob_clean();

        $this->expectOutputString($cyanText);
        $cli->text($testText)->colour('cyan')->write();
        ob_clean();

        $this->expectOutputString($whiteText);
        $cli->text($testText)->colour('white')->write();
    }

    /**
     * Test text()->colour()->colour()->write() 
     *
     */
    public function testTextColourColourWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text';
        
        $whiteThenRedText = "\033[37m\033[31m".$testText."\033[0m";

        /**
         * Tests and assertions
         */
        $this->expectOutputString($whiteThenRedText);
        $cli->text($testText)->colour('white')->colour('red')->write();
    }


    /**
     * Test text()->backgroundColour()->write() 
     *
     */
    public function testTextBackgroundColourWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

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
        $cli->text($testText)->backgroundColour('black')->write();
        ob_clean();
        $this->expectOutputString($blackText);
        $cli->text($testText)->backgroundColor('black')->write(); // americans exist
        ob_clean();

        $this->expectOutputString($redText);
        $cli->text($testText)->backgroundColour('red')->write();
        ob_clean();

        $this->expectOutputString($greenText);
        $cli->text($testText)->backgroundColour('green')->write();
        ob_clean();

        $this->expectOutputString($yellowText);
        $cli->text($testText)->backgroundColour('yellow')->write();
        ob_clean();

        $this->expectOutputString($blueText);
        $cli->text($testText)->backgroundColour('blue')->write();
        ob_clean();

        $this->expectOutputString($magentaText);
        $cli->text($testText)->backgroundColour('magenta')->write();
        ob_clean();

        $this->expectOutputString($cyanText);
        $cli->text($testText)->backgroundColour('cyan')->write();
        ob_clean();

        $this->expectOutputString($whiteText);
        $cli->text($testText)->backgroundColour('white')->write();
    }

    /**
     * Test text()->style()->write() 
     *
     */
    public function testTextStyleWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

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
        $cli->text($testText)->style('bold')->write();
        ob_clean();

        $this->expectOutputString($italicText);
        $cli->text($testText)->style('italic')->write();
        ob_clean();

        $this->expectOutputString($underlineText);
        $cli->text($testText)->style('underline')->write();
        ob_clean();

        $this->expectOutputString($strikeText);
        $cli->text($testText)->style('strike')->write();
        ob_clean();
        $this->expectOutputString($strikeText);
        $cli->text($testText)->style('strikethrough')->write();
        ob_clean();

        $this->expectOutputString($boldItalicText);
        $cli->text($testText)->style('bold', 'italic')->write();
    }

    /**
     * Test text()->right()->write() 
     *
     */
    public function testTextRightWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text';

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->text($testText)->right()->write();
    }

    /**
     * Test text()->centre()->write() 
     *
     */
    public function testTextCentreWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text';

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->text($testText)->centre()->write();
        ob_clean();

        $this->expectOutputRegex('/^\\s+'.$testText.'$/');
        $cli->text($testText)->center()->write();
    }

    /**
     * Test text()->left()->write() 
     *
     */
    public function testTextLeftWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $testText = 'some text';

        /**
         * Tests and assertions
         */
        $this->expectOutputRegex('/^'.$testText.'$/');
        $cli->text($testText)->left()->write();
    }

    public function testTextLevels()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

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
        $cli->text($testText)->ok();
        ob_clean();

        $this->expectOutputString($debugText);
        $cli->text($testText)->debug();
        ob_clean();

        $this->expectOutputString($infoText);
        $cli->text($testText)->info();
        ob_clean();

        $this->expectOutputString($noticeText);
        $cli->text($testText)->notice();
        ob_clean();

        $this->expectOutputString($warningText);
        $cli->text($testText)->warning();
        ob_clean();

        $this->expectOutputString($alertText);
        $cli->text($testText)->alert();
        ob_clean();

        $this->expectOutputString($errorText);
        $cli->text($testText)->error();
        ob_clean();

        $this->expectOutputString($criticalText);
        $cli->text($testText)->critical();
        ob_clean();

        $this->expectOutputString($emergencyText);
        $cli->text($testText)->emergency();
    }

    /**
     * applyAnsiWrapper() plain text
     *
     */
    public function testApplyAnsiWrapperPlain()
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');

        /**
         * Data
         */
        $testText =<<<TXT
        this is the test text for testing wrapping that is ANSI safe. we are testing this on thirty cols. this line ends on on.

        New paragraph.


        New paragraph after two PHP_EOL.
        TXT;

        $expectsText =<<<TXT
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
     */
    public function testApplyAnsiWrapperStyled()
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');

        /**
         * Data
         */
        $testText =<<<TXT
        this is the \033[31m\033[1m\033[3mtest\033[0m text for testing wrapping that is ANSI safe. we are testing this on thirty cols. this line ends on on.

        New paragraph.


        New paragraph after two PHP_EOL.
        TXT;

        $expectsText =<<<TXT
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
     */
    public function testPageOneLine()
    {

        $testText = '';
        for($i=0;$i<100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }

        /**
         * Mock getKeystroke()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameText::class)->setConstructorArgs([$testText])->onlyMethods(['readKeystroke'])->getMock();
        $mockedMacrame->expects($this->any())->method('readKeystroke')->will($this->returnValue(chr(10)));

        /**
         * Test and assertion
         */
        $this->expectOutputRegex("/^line 0/i");
        $mockedMacrame->page();
    }

    /**
     * Test page()
     * <SPACE>
     */
    public function testPageOnePage()
    {

        $testText = '';
        for($i=0;$i<100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }

        /**
         * Mock getKeystroke()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameText::class)->setConstructorArgs([$testText])->onlyMethods(['readKeystroke'])->getMock();
        $mockedMacrame->expects($this->any())->method('readKeystroke')->will($this->returnValue(chr(32)));

        /**
         * Test and assertion
         */
        $this->expectOutputRegex("/^line 0/i");
        $mockedMacrame->page();
    }

    /**
     * Test page()
     * <q>
     */
    public function testPageQuit()
    {
        $testText = '';
        for($i=0;$i<100;$i++) {
            $testText .= "line ".$i.PHP_EOL;
        }

        /**
         * Mock getKeystroke()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameText::class)->setConstructorArgs([$testText])->onlyMethods(['readKeystroke'])->getMock();
        $mockedMacrame->expects($this->any())->method('readKeystroke')->will($this->returnValue('q'));

        /**
         * Test and assertion
         */
        $this->expectOutputRegex("/^line 0/i");
        $mockedMacrame->page();
    }

    /**
     * mb_strwidth_ansi() 
     *
     * @dataProvider strlenProvider
     */
    public function testStrlenAnsiSafe($string, $length)
    {
        $cliText = new \Gbhorwood\Macrame\MacrameText('');
        $this->assertEquals($cliText->mb_strwidth_ansi($string), $length);
    }

    /**
     * Provides strings and lengths to test mb_strwidth_ansi() 
     *
     * @return Array
     */
    public static function strlenProvider():Array
    {

        $italic = "\033[3m";
        $red = "\033[31m";
        $close = "\033[0m";

        return [
            ["string!", 7],
            ["striñg!", 7],         // non-roman
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