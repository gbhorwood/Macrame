<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Tell phpunit when using processIsolation what STDIN is
 */
if(!defined('STDIN')) define('STDIN', fopen("php://stdin","r"));

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameMenu::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameIO::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameMenu::class)]
class MenuTest extends TestCase
{

    use \phpmock\phpunit\PHPMock;

    /**
     * Test interactive()
     * down arrow
     *
     * @runInSeparateProcess
     */
    public function testInteractiveDownArrow()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'three',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->interactive($options, $header);

        /**
         * assertions
         */
        $this->assertEquals('two', $result);
        ob_end_clean();
    }

    /**
     * Test interactive()
     * down arrow. styling.
     *
     * @runInSeparateProcess
     */
    public function testInteractiveDownArrowStyling()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'three',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->colorOption('green')->colorSelected('red')->styleOption('italic')->styleSelected('bold')->interactive($options, $header);

        /**
         * assertions
         */
        $this->assertEquals('two', $result);
        ob_end_clean();
    }

    /**
     * Test interactive()
     * tab
     *
     * @runInSeparateProcess
     */
    public function testInteractiveTab()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(9),  // tab
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'three',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->interactive($options, $header);

        /**
         * assertions
         */
        $this->assertEquals('two', $result);
        ob_end_clean();
    }

    /**
     * Test interactive()
     * up arrow
     *
     * @runInSeparateProcess
     */
    public function testInteractiveUpArrow()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(65), // up arrow
            chr(65), // up arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'three',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->interactive($options, $header);

        /**
         * assertions
         */
        $this->assertEquals('two', $result);
        ob_end_clean();
    }

    /**
     * Test interactive()
     * leader keys
     *
     * @runInSeparateProcess
     */
    public function testInteractiveLeaderKeys()
    {
        /**
         * data
         */
        $keystrokes = [
            't',     // leader key
            'w',     // leader key
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'three',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->interactive($options, $header);

        /**
         * assertions
         */
        $this->assertEquals('two', $result);
        ob_end_clean();
    }

    /**
     * Test interactive()
     * option right
     *
     * @runInSeparateProcess
     */
    public function testInteractiveOptionRight()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/   head/');
        $result = $input->optionRight()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * option centre
     *
     * @runInSeparateProcess
     */
    public function testInteractiveOptionCentre()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head     /');
        $result = $input->optionCentre()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * option center
     *
     * @runInSeparateProcess
     */
    public function testInteractiveOptionCenter()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head     /');
        $result = $input->optionCenter()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * option left
     *
     * @runInSeparateProcess
     */
    public function testInteractiveOptionLeft()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head/');
        $result = $input->optionCentre()->optionLeft()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * menu left
     *
     * @runInSeparateProcess
     */
    public function testInteractiveMenuLeft()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head/');
        $result = $input->optionCentre()->menuLeft()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * menu right
     *
     * @runInSeparateProcess
     */
    public function testInteractiveMenuRight()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head/');
        $result = $input->optionCentre()->menuRight()->interactive($options, $header);
    }

    /**
     * Test interactive()
     * menu center
     *
     * @runInSeparateProcess
     */
    public function testInteractiveMenuCenter()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(66), // down arrow
            chr(10), // return
        ];

        $header = 'head';

        $options = [
            'one',
            'two',
            'headxxx',
        ];

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex('/ head/');
        $result = $input->optionCentre()->menuCenter()->interactive($options, $header);
    }

    /**
     * Test datePicker()
     * arrow keys
     *
     * @runInSeparateProcess
     */
    public function testDatePickerArrowKeys()
    {
        /**
         * data
         */
        $keystrokes = [
            chr(67), // right arrow
            chr(68), // left arrow
            chr(66), // down arrow
            chr(9),  // tab
            chr(65), // up arrow
            chr(65), // up arrow
            chr(9),  // tab
            chr(66), // down arrow
            chr(66), // down arrow
            chr(66), // down arrow
            chr(10), // return
        ];

        // aug

        $header = 'head';

        $date = "1990-10-04";

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->datePicker($date, $header);

        /**
         * assertions
         */
        $this->assertEquals('1991-08-07', $result);
        ob_end_clean();
    }

    /**
     * Test datePicker()
     * leader keys
     *
     * @runInSeparateProcess
     */
    public function testDatePickerLeaderKeys()
    {
        /**
         * data
         */
        $keystrokes = [
            '2',
            chr(9),  // tab
            'n',
            chr(8),  // backsapce
            'o',
            chr(9),  // tab
            '3',
            chr(10), // return
        ];

        $header = 'head';

        $date = "1990-10-04";

        /**
         * Override stream_get_contents() to return $keystrokes
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$keystrokes);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->datePicker($date, $header);

        /**
         * assertions
         */
        $this->assertEquals('2000-11-03', $result);
        ob_end_clean();
    }


    /**
     * Test datePicker()
     * invalid date string
     *
     * @runInSeparateProcess
     */
    public function testDatePickerInvalidDateString()
    {

        $header = 'head';

        $date = "notadate";

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameMenu(new \Gbhorwood\Macrame\MacrameText());

        $this->expectOutputRegex("/Provided string 'notadate' is not a valid date/");

        $result = $input->datePicker($date, $header);

        /**
         * assertions
         */
        $this->assertEquals($date, $result);
    }
}
