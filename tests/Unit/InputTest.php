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
#[CoversClass(\Gbhorwood\Macrame\MacrameInput::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameIO::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameInput::class)]
class InputTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Test readline
     *
     */
    public function testReadline()
    {
        $userInput = "a line of user input";

        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->once())->willReturn($userInput);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $result = $input->readline();

        $this->assertEquals($result, $userInput);
    }

    /**
     * Test isEmail()->readline()
     *
     */
    public function testReadlineWithIsEmail()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = 'ghorwood@example.ca';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->isEmail($errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * Test isUrl()->readline()
     *
     */
    public function testReadlineWithIsUrl()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = 'https://example.ca?foo=bar';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->isUrl($errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }


    /**
     * Test isIpAddress()->readline()
     *
     */
    public function testReadlineWithIsIpAddress()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = '182.222.45.3';
        $userInputs = [
            '1.344.899.0',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->isIpAddress($errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test islengthmin()->readline()
     *
     */
    public function testReadlineWithIsLengthMin()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = '1234567';
        $userInputs = [
            '1234',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isLengthMin(5, $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isLengthMax()->readline()
     *
     */
    public function testReadlineWithIsLengthMax()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = '1234';
        $userInputs = [
            '1234567',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isLengthMax(5, $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isEntropyMin()->readline()
     *
     */
    public function testReadlineWithIsEntropyMin()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'some#con!tent';
        $userInputs = [
            'eeeeeeeeee',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isEntropyMin(2.5, $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test doesContain()->readline()
     *
     */
    public function testReadlineWithDoesContain()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'somefoobar';
        $userInputs = [
            'bazquux',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->doesContain('foo', $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test doesNotContain()->readline()
     *
     */
    public function testReadlineWithDoesNotContain()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'bazquux';
        $userInputs = [
            'somefoobar',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->doesNotContain('foo', $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * Test isInt()->readline()
     *
     */
    public function testReadlineWithIsInt()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = '21';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->isInt($errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * Test isNumber()->readline()
     *
     */
    public function testReadlineWithIsNumber()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = '21.5';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->isNumber($errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isOneOf()->readline()
     *
     */
    public function testReadlineWithIsOneOf()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'thisdata';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isOneOf(['thisdata', 'thisotherdata'], $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isEqualTo()->readline()
     *
     */
    public function testReadlineWithIsEqualTo()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'thisdata';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isEqualTo($validInput, $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isPregMatch()->readline()
     *
     */
    public function testReadlineWithIsPregMatch()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'thisdata';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isPregMatch("/$validInput/", $erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * test isDate()->readline()
     *
     */
    public function testReadlineWithIsDate()
    {
        /**
         * data
         */
        $errorOutput = uniqid();
        $validInput = 'Jan 12th, 1993';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * mocks
         */
        $readline = $this->getFunctionMock('gbhorwood\macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$erroroutput/");
        $result = $input->isDate($erroroutput)->readline();

        /**
         * assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * Test readPassword
     *
     * @runInSeparateProcess
     */
    public function testReadPassword()
    {
        $password = "woper";
        $passwordChars = str_split($password);
        $passwordChars[] = chr(10);

        /**
         * Override stream_get_contents() to return $validPassword one char
         * at a time
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$passwordChars);

        /**
         * Override fwrite() to suppress dots echo to STDOUT
         */
        $fwrite = $this->getFunctionMock('Gbhorwood\Macrame' , "fwrite");
        $fwrite->expects($this->any())
                 ->willReturnOnConsecutiveCalls(null);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->readPassword();

        /**
         * assertions
         */
        $this->assertEquals($password, $result);
        ob_end_clean();
    }

    /**
     * Test readPassword
     * backspaces
     *
     * @runInSeparateProcess
     */
    public function testReadPasswordBackspaces()
    {
        $password = "foobarer".chr(127).chr(127)."zz";
        $expected = "foobarzz";
        $passwordChars = str_split($password);
        $passwordChars[] = chr(10);

        /**
         * Override stream_get_contents() to return $validPassword one char
         * at a time
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$passwordChars);

        /**
         * Override fwrite() to suppress dots echo to STDOUT
         */
        $fwrite = $this->getFunctionMock('Gbhorwood\Macrame' , "fwrite");
        $fwrite->expects($this->any())
                 ->willReturnOnConsecutiveCalls(null);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->readPassword();

        /**
         * assertions
         */
        $this->assertEquals($expected, $result);
        ob_end_clean();
    }

    /**
     * Test readPassword
     * validations
     *
     * @dataProvider passwordProviderSuccess
     * @runInSeparateProcess
     */
    public function testReadPasswordWithValidations($password, $expected)
    {
        $passwordChars = str_split($password);
        $passwordChars[] = chr(10);

        /**
         * Override stream_get_contents() to return $validPassword one char
         * at a time
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$passwordChars);

        /**
         * Override fwrite() to suppress dots echo to STDOUT
         */
        $fwrite = $this->getFunctionMock('Gbhorwood\Macrame' , "fwrite");
        $fwrite->expects($this->any())
                 ->willReturnOnConsecutiveCalls(null);

        /**
         * test
         */
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        ob_start();
        $result = $input->isLengthMin(5, $errorOutput)->isLengthMax(10, $errorOutput)->readPassword();

        /**
         * assertions
         */
        $this->assertEquals($expected, $result);
        ob_end_clean();
    }

    /**
     * Test readPipe()
     *
     */
    public function testReadPipe()
    {
        $validPipedContent =<<<TXT
        line one
        line two
        TXT;
        $validPipedContentArray = array_map(fn($line) => $line.PHP_EOL, explode(PHP_EOL, $validPipedContent));

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(true);

        /**
         * Override fgets to return valid content instead of STDIN
         */
        $fgets = $this->getFunctionMock('Gbhorwood\Macrame', "fgets");
        $fgets->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$validPipedContentArray);


        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $pipedContent = $input->readPipe();

        $this->assertEquals($validPipedContent, trim($pipedContent));
    }

    /**
     * Test readPipe()
     * No content
     *
     */
    public function testReadPipeNoContent()
    {
        /**
         * Override stream_select to return false
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(false);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $pipedContent = $input->readPipe();

        $this->assertEquals(null, $pipedContent);
    }

    /**
     * Test readPipeByLine()
     *
     */
    public function testReadPipeByLine()
    {
        $validPipedContent =<<<TXT
        line one
        line two
        TXT;
        $validPipedContentArray = array_map(fn($line) => $line.PHP_EOL, explode(PHP_EOL, $validPipedContent));

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(true);

        /**
         * Override fgets to return valid content instead of STDIN
         */
        $fgets = $this->getFunctionMock('Gbhorwood\Macrame', "fgets");
        $fgets->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$validPipedContentArray);


        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $i = 0;
        foreach($input->readPipeByLine() as $line) {
            $this->assertEquals(trim($line), trim($validPipedContentArray[$i]));
            $i++;
        }
    }

    /**
     * Test readKey()
     *
     */
    public function testReadKey()
    {
        $char = 'j';
        $prompt = "some prompt";

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(true);

        /**
         * Override stream_get_contents() to return $char
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->once())->willReturn($char);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $this->expectOutputRegex("/$prompt/");
        $keyContent = $input->readKey($prompt);

        $this->assertEquals($char, $keyContent);
    }

    /**
     * Test readOption()
     *
     */
    public function testReadOption()
    {
        $char = 'j';
        $prompt = "some prompt";
        $options = ['h', 'i', 'j'];
        $default = 'k';

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(true);

        /**
         * Override stream_get_contents() to return $char
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->once())->willReturn($char);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $this->expectOutputRegex("/$prompt/");
        $keyContent = $input->readOption($options, $default, $prompt, 'invalid option');

        $this->assertEquals($char, $keyContent);
    }

    /**
     * Test readOptionDefault()
     *
     */
    public function testReadOptionDefault()
    {
        $char = chr(10);
        $prompt = "some prompt";
        $options = ['h', 'i', 'j'];
        $default = 'k';

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->once())->willReturn(true);

        /**
         * Override stream_get_contents() to return $char
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->once())->willReturn($char);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $this->expectOutputRegex("/$prompt/");
        $keyContent = $input->readOption($options, $default, $prompt, 'invalid option');

        $this->assertEquals($default, $keyContent);
    }

    /**
     * Test readKey()
     * isOneOf
     */
    public function testReadKeyIsOneOf()
    {
        $chars = ['a', 'n'];
        $streamSelects = [true, true];
        $prompt = "some prompt";
        $errorMessage = "some error";

        /**
         * Override stream_select to return true
         */
        $streamSelect = $this->getFunctionMock('Gbhorwood\Macrame', "stream_select");
        $streamSelect->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$streamSelects);

        /**
         * Override stream_get_contents() to return $char
         */
        $streamGetContents = $this->getFunctionMock('Gbhorwood\Macrame' , "stream_get_contents");
        $streamGetContents->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$chars);

        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());

        $this->expectOutputRegex("/$errorMessage/");
        $keyContent = $input->isOneOf(['y', 'n'], $errorMessage)->readKey();

        $this->assertEquals('n', $keyContent);
    }

    /**
     * Test addValidator()
     *
     */
    public function testAddValidator()
    {
        /**
         * Data
         */
        $errorOutput = uniqid();
        $validInput = 'VALID';
        $userInputs = [
            'notvalid',
            $validInput,
        ];

        /**
         * Mocks
         */
        $readline = $this->getFunctionMock('Gbhorwood\Macrame', "readline");
        $readline->expects($this->any())
                 ->willReturnOnConsecutiveCalls(...$userInputs);

        /**
         * Test
         */
        $isUpper = fn($text) => strtoupper($text) == $text;
        $input = new \Gbhorwood\Macrame\MacrameInput(new \Gbhorwood\Macrame\MacrameText());
        $this->expectOutputRegex("/$errorOutput/");
        $result = $input->addValidator($isUpper, $errorOutput)->readline();

        /**
         * Assertions
         */
        $this->assertEquals($result, $validInput);
    }

    /**
     * Provider for successful passwords 
     *
     * @return Array
     */
    public static function passwordProviderSuccess():Array
    {

        return [
            ['password', 'password'],
            ['password'.chr(127), 'passwor'],
            ['password'.chr(127).'y', 'passwory'],
        ];
    }
}