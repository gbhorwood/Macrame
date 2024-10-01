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
     * tab
     *
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
     * menu left
     *
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
}
