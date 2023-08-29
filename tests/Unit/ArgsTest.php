<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameArgs::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameArgs::class)]
class ArgsTest extends TestCase
{
    /**
     * Test parseArgs()
     *
     * @dataProvider argvProvider
     */
    public function testArgs($argv, $expected)
    {
        /**
         * Tests and  Assertions
         */
        foreach($expected as $argName => $results) {
            $GLOBALS['argv'] = $argv;

            $cli = new \Gbhorwood\Macrame\MacrameArgs($argName);
            $this->assertEquals($cli->count(), $results['count']);
            $this->assertEquals($cli->first(), $results['first']);
            $this->assertEquals($cli->last(), $results['last']);
            $this->assertEquals($cli->all(), $results['all']);
            $this->assertEquals($cli->exists(), true);

            $cli = new \Gbhorwood\Macrame\MacrameArgs('positional');
            $this->assertEquals($cli->all(), $results['positional']);
        }
    }

    /**
     * Test parseArgs()
     * missing arg
     *
     */
    public function testArgsMissing()
    {
        $cli = new \Gbhorwood\Macrame\MacrameArgs('nonexistant');
        $this->assertEquals(0, $cli->count());
        $this->assertEquals(null, $cli->first());
        $this->assertEquals([], $cli->all());
    }

    /**
     * Provide $argv and expeted content
     *
     * @return Array
     */
    public static function argvProvider():Array
    {
        return [
            [ ['macrame', '--foo', '--foo'], ['foo' => ['count' => 2, 'first' => null, 'last' => null, 'all' => [], 'positional' => [] ]] ],
            [ ['macrame', '--foo=someval', '--foo'], ['foo' => ['count' => 2, 'first' => 'someval', 'last' => 'someval', 'all' => ['someval'], 'positional' => [] ]] ],
            [ ['macrame', '--foo', '--foo=someval'], ['foo' => ['count' => 2, 'first' => 'someval', 'last' => 'someval', 'all' => ['someval'], 'positional' => [] ]] ],
            [ ['macrame', '-fff'], ['f' => ['count' => 3, 'first' => null, 'last' => null, 'all' => [], 'positional' => [] ]] ],
            [ ['macrame', '-f', '-f', '-f'], ['f' => ['count' => 3, 'first' => null, 'last' => null, 'all' => [], 'positional' => [] ]] ],
            [ ['macrame', 'positional1', 'positional2'], ['positional' => ['count' => 2, 'first' => 'positional1', 'last' => 'positional2', 'all' => ['positional1', 'positional2'], 'positional' => ['positional1', 'positional2'] ]] ],
        ];
    } // argvProvider
}