<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Gbhorwood\Macrame\MacrameIO::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameIO::class)]
class IOTest extends TestCase
{

    use \phpmock\phpunit\PHPMock;

    /**
     * Test getColWidth()
     *
     * @dataProvider sttySizeProvider
     */
    public function testGetColWidth($height, $width, $expectedHeight, $expectedWidth)
    {
        $sttySize = (string)$height.' '.$width;
        $fread = $this->getFunctionMock('Gbhorwood\Macrame', "fread");
        $fread->expects($this->once())->willReturn($sttySize);

        $this->assertEquals($expectedWidth, \Gbhorwood\Macrame\MacrameIO::getColWidth());
    }

    /**
     * Test getRowHeight()
     *
     * @dataProvider sttySizeProvider
     */
    public function testGetRowHeight($height, $width, $expectedHeight, $expectedWidth)
    {
        $sttySize = (string)$height.' '.$width;
        $fread = $this->getFunctionMock('Gbhorwood\Macrame', "fread");
        $fread->expects($this->once())->willReturn($sttySize);

        $this->assertEquals($expectedHeight, \Gbhorwood\Macrame\MacrameIO::getRowHeight());
    }

    /**
     * Provides 
     *
     * @return Array
     */
    public static function sttySizeProvider():Array
    {

        return [
            [ 25, 80, 25, 80 ],
            [ 25, 79, 25, 79 ],
            [ 25, 81, 25, 80 ],
            [ 25, null, 25, 80 ],
            [ null, 80, 25, 80 ],
        ];
    }
}
