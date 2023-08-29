<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Gbhorwood\Macrame\Macrame::class)]
#[CoversClass(\Gbhorwood\Macrame\MacrameTable::class)]
#[UsesClass(\Gbhorwood\Macrame\Macrame::class)]
#[UsesClass(\Gbhorwood\Macrame\MacrameTable::class)]
class TableTest extends TestCase
{
    /**
     * Test table()->get()
     *
     * @dataProvider tableProvider
     */
    public function testTableGet($headers, $data, $expected, $left=null, $right=null, $centre=null)
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Test
         */
        $table = $cli->table($headers, $data);
        $table = $centre ? $table->centre($centre) : $table;
        $table = $right ? $table->right($right) : $table;
        $table = $left ? $table->left($left) : $table;
        $result = $table->get();

        /**
         * Assertions
         */
        // munge to array of lines for ease
        $resultLinesArray = array_map(fn($l) => trim($l), array_filter(explode(PHP_EOL, $result)));
        $expectedLinesArray = array_map(fn($l) => trim($l), array_filter(explode(PHP_EOL, $expected)));
        $this->assertEquals($expectedLinesArray, $resultLinesArray);
    }

    /**
     * Test table()->write()
     *
     */
    public function testTableWrite()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $headers = ["one", "two"];
        $data = [["data1", "data2"]];

        /**
         * Test
         */
        $tableOutput =<<<TXT
        +-------+-------+
        | one   | two   |
        +-------+-------+
        | data1 | data2 |
        +-------+-------+

        TXT;
        $this->expectOutputString($tableOutput);
        $cli->table($headers, $data)->write();

    }

    /**
     * Test table()->get()
     * ColumnMismatch
     *
     */
    public function testTableGetColumnMismatch()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $headers = ["one", "two", "three"];
        $data = [["one", "two"]];

        /**
         * Test and assertions
         */
        $this->expectOutputRegex('/Table column mismatch/');
        $table = $cli->table($headers, $data);
        $result = $table->get();
        ob_clean();
        
        /**
         * Data
         */
        $headers = ["one", "two", "three"];
        $data = [["one", "two", "three"], ["one", "two"]];

        /**
         * Test and assertions
         */
        $this->expectOutputRegex('/Table column mismatch/');
        $table = $cli->table($headers, $data);
        $result = $table->get();
    }

    /**
     * Test table->solid()->write()
     *
     */
    public function testTableSolid()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $headers = ["one", "two", "three",];
        $data = [["data1", "data2", "datac"], ['data3','data4', "datad"]];
        $tableOutput =<<<TXT
        ┌───────┬───────┬───────┐
        │ one   │  two  │ three │
        ├───────┼───────┼───────┤
        │ data1 │ data2 │ datac │
        │ data3 │ data4 │ datad │
        └───────┴───────┴───────┘

        TXT;

        /**
         * Test and assertions
         */
        $this->expectOutputString($tableOutput);
        $cli->table($headers, $data)->centre(1)->double()->solid()->write();
    }

    /**
     * Test table->double()->write()
     *
     */
    public function testTableDouble()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $headers = ["one", "two", "three",];
        $data = [["data1", "data2", "datac"], ['data3','data4', "datad"]];
        $tableOutput =<<<TXT
        ╔═══════╦═══════╦═══════╗
        ║ one   ║  two  ║ three ║
        ╠═══════╬═══════╬═══════╣
        ║ data1 ║ data2 ║ datac ║
        ║ data3 ║ data4 ║ datad ║
        ╚═══════╩═══════╩═══════╝

        TXT;

        /**
         * Test and assertions
         */
        $this->expectOutputString($tableOutput);
        $cli->table($headers, $data)->centre(1)->solid()->double()->write();
    }

    /**
     * Test table->standard()->write()
     *
     */
    public function testTableStandard()
    {
        $cli = new \Gbhorwood\Macrame\Macrame();

        /**
         * Data
         */
        $headers = ["one", "two", "three",];
        $data = [["data1", "data2", "datac"], ['data3','data4', "datad"]];
        $tableOutput =<<<TXT
        +-------+-------+-------+
        | one   |  two  | three |
        +-------+-------+-------+
        | data1 | data2 | datac |
        | data3 | data4 | datad |
        +-------+-------+-------+

        TXT;

        /**
         * Test and assertions
         */
        $this->expectOutputString($tableOutput);
        $cli->table($headers, $data)->centre(1)->solid()->standard()->write();
    }

    /**
     * Provide $argv and expeted content
     *
     * @return Array
     */
    public static function tableProvider():Array
    {
        $italic = "\033[3m";
        $red = "\033[31m";
        $close = "\033[0m";

        $headers1 = [ 'header one', 'header two', 'header three', ];
        $data1 = [ [ 'data one', 'data two', 'data three', ], [ 'second data one', 'second data two', 'second data three', ], ];
        $expected1 =<<<TXT
        +-----------------+-----------------+-------------------+
        | header one      | header two      | header three      |
        +-----------------+-----------------+-------------------+
        | data one        | data two        | data three        |
        | second data one | second data two | second data three |
        +-----------------+-----------------+-------------------+
        TXT;

        $headers2 = [ 'latin text', 'some emojis', 'latin text', ];
        $data2 = [ [ 'data one', '🌈rain🌈bow🌈', 'data three', ], [ 'second data one', 'noemojis', 'second data three', ], ];
        $expected2 =<<<TXT
        +-----------------+---------------+-------------------+
        | latin text      | some emojis   | latin text        |
        +-----------------+---------------+-------------------+
        | data one        | 🌈rain🌈bow🌈 | data three        |
        | second data one | noemojis      | second data three |
        +-----------------+---------------+-------------------+
        TXT;


        $headers3 = [ 'latin text', 'some emojis', 'latin text', ];
        $data3 = [ [ 'data one', $red.$italic."str🌈🌈ng".$close, "data three", ], [ 'second data one', 'jfjf', 'second data three', ], ];
        $expected3 =<<<TXT
        +-----------------+-------------+-------------------+
        | latin text      | some emojis | latin text        |
        +-----------------+-------------+-------------------+
        | data one        | ${red}${italic}str🌈🌈ng${close}   | data three        |
        | second data one | jfjf        | second data three |
        +-----------------+-------------+-------------------+
        TXT;

        $headers4 = [ 'latin text', 'some emojis', 'latin text', ];
        $data4 = [ [ 'data one', $red.$italic."str🌈🌈ng".$close, "data three", ], [ 'second data one', 'jfjf', 'second data three', ], ];
        $expected4 =<<<TXT
        +-----------------+-------------+-------------------+
        | latin text      | some emojis |        latin text |
        +-----------------+-------------+-------------------+
        | data one        | ${red}${italic}str🌈🌈ng${close}   |        data three |
        | second data one | jfjf        | second data three |
        +-----------------+-------------+-------------------+
        TXT;

        $headers5 = [ 'latin text', 'some emojis', 'latin text', ];
        $data5 = [ [ 'data one', $red.$italic."str🌈🌈ng".$close, "data three", ], [ 'second data one', 'jfjf', 'second data three', ], ];
        $expected5 =<<<TXT
        +-----------------+-------------+-------------------+
        | latin text      | some emojis | latin text        |
        +-----------------+-------------+-------------------+
        | data one        |  ${red}${italic}str🌈🌈ng${close}  | data three        |
        | second data one |    jfjf     | second data three |
        +-----------------+-------------+-------------------+
        TXT;

        $headers6 = [ 'latin text', 'some emojis', 'latin text', ];
        $data6 = [ [ 'data one', $red.$italic."str🌈🌈ng".$close, "data three", ], [ 'second data one', 'jfjf', 'second data three', ], ];
        $expected6 =<<<TXT
        +-----------------+-------------+-------------------+
        | latin text      | some emojis | latin text        |
        +-----------------+-------------+-------------------+
        | data one        | ${red}${italic}str🌈🌈ng${close}   | data three        |
        | second data one | jfjf        | second data three |
        +-----------------+-------------+-------------------+
        TXT;

        return [
            [$headers1, $data1, $expected1, null, null, null],
            [$headers2, $data2, $expected2, null, null, null],
            [$headers3, $data3, $expected3, null, null, null],
            [$headers4, $data4, $expected4, null, 2, null],
            [$headers5, $data5, $expected5, null, null, 1],
            [$headers6, $data6, $expected6, 1, null, 1],
        ];





    } // argvProvider
}