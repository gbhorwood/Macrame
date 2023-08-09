<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameFile::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameFile::class)]
class FileTest extends TestCase
{

    use \phpmock\phpunit\PHPMock;

    /**
     * Test readable()
     *
     */
    public function testReadable()
    {
        $urls = $this->buildFilesystem("some content");

        $cli = new \Gbhorwood\Macrame\Macrame();
        $this->assertTrue($cli->file($urls['file_open'])->readable());
        $this->assertTrue($cli->file($urls['file_readonly'])->readable());
        $this->assertFalse($cli->file($urls['file_writeonly'])->readable());
        $this->assertFalse($cli->file('vfs://dir/nonexistant')->readable());
    }

    /**
     * Test writeable()
     *
     */
    public function testWriteable()
    {
        $urls = $this->buildFilesystem("some content");

        $cli = new \Gbhorwood\Macrame\Macrame();
        $this->assertTrue($cli->file($urls['file_open'])->writable());
        $this->assertTrue($cli->file($urls['file_writeonly'])->writable());
        $this->assertTrue($cli->file($urls['dir_public'].'/newfile')->writable());
        $this->assertFalse($cli->file($urls['file_readonly'])->writable());
        $this->assertFalse($cli->file($urls['dir_private'].'/newfile')->writable());
        $this->assertFalse($cli->file('vfs://dir/nonexistant')->writable());
    }

    /**
     * Test clobbers()
     *
     */
    public function testClobbers()
    {
        $urls = $this->buildFilesystem("some content");

        $cli = new \Gbhorwood\Macrame\Macrame();
        $this->assertTrue($cli->file($urls['file_open'])->clobbers());
        $this->assertFalse($cli->file($urls['dir_public'].'/newfile')->clobbers());
    }

    /**
     * Test byteCount()
     *
     * @dataProvider bytecountProvider
     */
    public function testByteCount(String $text, Int $count)
    {
        $urls = $this->buildFilesystem("some content");
        $cli = new \Gbhorwood\Macrame\Macrame();
        $file = $cli->file($urls['file_open']);

        $this->assertEquals($count, $file->byteCount($text));
    }

    /**
     * Test byLine()
     *
     */
    public function testByLine()
    {
        $content = null;
        for($i=0;$i<100;$i++) {
            $content .= uniqid()."One line of text".PHP_EOL;
        }
        $contentArray = array_filter(explode(PHP_EOL, $content));
        $urls = $this->buildFilesystem($content);

        $cli = new \Gbhorwood\Macrame\Macrame();

        $generator = $cli->file($urls['file_open']);

        $i = 0;
        foreach($generator->byLine() as $line) {
            $this->assertEquals(trim($line), trim($contentArray[$i]));
            $i++;
        }
    }

    /**
     * Test write()
     *
     */
    public function testWrite()
    {
        $testContent = "BEHOLD TEST CONTENT";
        $urls = $this->buildFilesystem("some content");

        /**
         * Mock enoughSpace()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameFile::class)->setConstructorArgs([$urls['file_open']])->onlyMethods(['enoughSpace'])->getMock();
        $mockedMacrame->expects($this->any())->method('enoughSpace')->will($this->returnValue(true));

        $mockedMacrame->write($testContent);

        $this->assertEquals($testContent, file_get_contents($urls['file_open']));
    }

    /**
     * Test write()
     * No disk space
     *
     */
    public function testWriteNoDiskSpace()
    {
        $testContent = "BEHOLD TEST CONTENT";
        $urls = $this->buildFilesystem("some content");

        /**
         * Mock enoughSpace()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameFile::class)->setConstructorArgs([$urls['file_open']])->onlyMethods(['enoughSpace'])->getMock();
        $mockedMacrame->expects($this->any())->method('enoughSpace')->will($this->returnValue(false));

        $this->expectOutputRegex("/WARNING/");
        $mockedMacrame->write($testContent);
    }

    /**
     * Test write()
     * No write access
     *
     */
    public function testWriteNoWriteAccess()
    {
        $testContent = "BEHOLD TEST CONTENT";
        $urls = $this->buildFilesystem("some content");

        /**
         * Mock enoughSpace()
         */
        $mockedMacrame = $this->getMockBuilder(\Gbhorwood\Macrame\MacrameFile::class)->setConstructorArgs([$urls['file_readonly']])->onlyMethods(['enoughSpace'])->getMock();
        $mockedMacrame->expects($this->any())->method('enoughSpace')->will($this->returnValue(true));

        $this->expectOutputRegex("/WARNING/");
        $mockedMacrame->write($testContent);
    }

    /**
     * Test byLine() unreadable file
     *
     */
    public function testByLine_unreadable()
    {
        $content = null;
        for($i=0;$i<100;$i++) {
            $content .= uniqid()."One line of text".PHP_EOL;
        }
        $urls = $this->buildFilesystem($content);

        $cli = new \Gbhorwood\Macrame\Macrame();

        $generator = $cli->file($urls['file_writeonly']);

        $this->expectOutputRegex("/WARNING/");

        foreach($generator->byLine() as $line) {
        }
    }

    /**
     * Scaffold the vfs filesystem for tests
     *
     * @param  String $content The content of all files
     * @return Array
     */
    private function buildFilesystem(String $content):Array
    {
        $root = vfsStream::setup('home');
        $public = vfsStream::newDirectory('public', 0777)->at($root); // rwxrwxrwx
        $private = vfsStream::newDirectory('private', 0111)->at($root); // --x--x--x
        $urls = [
            'dir_public' => $public->url(),
            'dir_private' => $private->url(),
            'file_open' => vfsStream::newFile('open', 0777)->at($public)->withContent($content)->url(), // rwxrwxrwx
            'file_writeonly' => vfsStream::newFile('writeonly', 0222)->at($public)->withContent($content)->url(), // -w--w--w-
            'file_readonly' => vfsStream::newFile('readonly', 0444)->at($public)->withContent($content)->url(), // r--r--r--
        ];
        return $urls;
    }

    /**
     * Provides strings and bytecounts to test byteCount() 
     *
     * @return Array
     */
    public static function bytecountProvider():Array
    {
        return [
            ['i am damo', 9],
            ['한국어로 된 문자열', 26],
            ['한국어로 된 문자열 and this is latin', 44],
            ['🔋🪫🔌💻🖥️🖨️⌨️🖱️🖲️💽💾💿📀🧮', 70],
        ];
    }
}