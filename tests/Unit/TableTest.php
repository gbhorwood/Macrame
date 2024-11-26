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
     * @runInSeparateProcess
     * @dataProvider tableProvider
     */
    public function testTableGet($headers, $data, $expected, $style, $lefts, $rights, $centres)
    {

        $table = new \Gbhorwood\Macrame\MacrameTable($headers, $data);

        array_map(fn($col) => $table->left($col), $lefts);
        array_map(fn($col) => $table->right($col), $rights);
        array_map(fn($col) => $table->centre($col), $centres);

        if($style == 'standard') {
            $table->standard();
        }
        if($style == 'solid') {
            $table->solid();
        }
        if($style == 'double') {
            $table->double();
        }

        $result = $table->get();

        $resultLinesArray = array_map(fn($l) => trim($l), array_filter(explode(PHP_EOL, $result)));
        $expectedLinesArray = array_map(fn($l) => trim($l), array_filter(explode(PHP_EOL, $expected)));

        //print_r($resultLinesArray);

        $this->assertEquals($expectedLinesArray, $resultLinesArray);
    }

    /**
     * Test table()->get()
     * Test center alias
     *
     */
    public function testTableGetCenter()
    {
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];

        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |   cola 0    | cola 1     | cola 2     |
        |   colb 0    | colb 1     | colb 2     |
        +-------------+------------+------------+
        TXT;


        $table = new \Gbhorwood\Macrame\MacrameTable($headers, $data);
        $table->center(0);

        $result = $table->get();

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
     * Provide $argv and expeted content
     *
     * @return Array
     */
    public static function tableProvider():Array
    {
        $italic = "\033[3m";
        $red = "\033[31m";
        $close = "\033[0m";

        $provided = [];

        // data 0
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [0, 1, 2];
        $rights = [];
        $centres = [];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        | cola 0      | cola 1     | cola 2     |
        | colb 0      | colb 1     | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 1
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [0, 1, 2];
        $rights = [];
        $centres = [];
        $style = 'solid';
        $expected =<<<TXT
        ┌─────────────┬────────────┬────────────┐
        │ header zero │ header one │ header two │
        ├─────────────┼────────────┼────────────┤
        │ cola 0      │ cola 1     │ cola 2     │
        │ colb 0      │ colb 1     │ colb 2     │
        └─────────────┴────────────┴────────────┘
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 2
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [0, 1, 2];
        $rights = [];
        $centres = [];
        $style = 'double';
        $expected =<<<TXT
        ╔═════════════╦════════════╦════════════╗
        ║ header zero ║ header one ║ header two ║
        ╠═════════════╬════════════╬════════════╣
        ║ cola 0      ║ cola 1     ║ cola 2     ║
        ║ colb 0      ║ colb 1     ║ colb 2     ║
        ╚═════════════╩════════════╩════════════╝
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 3
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [1, 2];
        $rights = [0];
        $centres = [];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |      cola 0 | cola 1     | cola 2     |
        |      colb 0 | colb 1     | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 4
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [2];
        $rights = [0, 1];
        $centres = [];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |      cola 0 |     cola 1 | cola 2     |
        |      colb 0 |     colb 1 | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 5
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [];
        $rights = [0, 1, 2];
        $centres = [];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |      cola 0 |     cola 1 |     cola 2 |
        |      colb 0 |     colb 1 |     colb 2 |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 6
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [1, 2];
        $rights = [];
        $centres = [0];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |   cola 0    | cola 1     | cola 2     |
        |   colb 0    | colb 1     | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 7
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [2];
        $rights = [];
        $centres = [0, 1];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |   cola 0    |   cola 1   | cola 2     |
        |   colb 0    |   colb 1   | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 8
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', 'cola 1', 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [];
        $rights = [];
        $centres = [0, 1, 2];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        |   cola 0    |   cola 1   |   cola 2   |
        |   colb 0    |   colb 1   |   colb 2   |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 9
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------------+---------------+
        | header zero |    header one    |    header two |
        +-------------+------------------+---------------+
        | cola 0 左边 | cola 1 가운데 열 | cola 2 右の列 |
        | colb 0      |      colb 1      |        colb 2 |
        +-------------+------------------+---------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];
        
        // data 10
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------------+---------------+
        | header zero |    header one    |    header two |
        +-------------+------------------+---------------+
        | cola 0 左边 | cola 1 가운데 열 | cola 2 右の列 |
        | colb 0 🤖   |    colb 1 🤖     |     colb 2 🤖 |
        +-------------+------------------+---------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 11
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'solid';
        $expected =<<<TXT
        ┌─────────────┬──────────────────┬───────────────┐
        │ header zero │    header one    │    header two │
        ├─────────────┼──────────────────┼───────────────┤
        │ cola 0 左边 │ cola 1 가운데 열 │ cola 2 右の列 │
        │ colb 0 🤖   │    colb 1 🤖     │     colb 2 🤖 │
        └─────────────┴──────────────────┴───────────────┘
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 12
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'double';
        $expected =<<<TXT
        ╔═════════════╦══════════════════╦═══════════════╗
        ║ header zero ║    header one    ║    header two ║
        ╠═════════════╬══════════════════╬═══════════════╣
        ║ cola 0 左边 ║ cola 1 가운데 열 ║ cola 2 右の列 ║
        ║ colb 0 🤖   ║    colb 1 🤖     ║     colb 2 🤖 ║
        ╚═════════════╩══════════════════╩═══════════════╝
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 13
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
            ['cola 0 左边'.PHP_EOL.'second line', 'cola 1 가운데 열'.PHP_EOL.'second line', 'cola 2 右の列'.PHP_EOL.'second line'],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'double';
        $expected =<<<TXT
        ╔═════════════╦══════════════════╦═══════════════╗
        ║ header zero ║    header one    ║    header two ║
        ╠═════════════╬══════════════════╬═══════════════╣
        ║ cola 0 左边 ║ cola 1 가운데 열 ║ cola 2 右の列 ║
        ║ colb 0 🤖   ║    colb 1 🤖     ║     colb 2 🤖 ║
        ║ cola 0 左边 ║ cola 1 가운데 열 ║ cola 2 右の列 ║
        ║ second line ║   second line    ║   second line ║
        ╚═════════════╩══════════════════╩═══════════════╝
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 14
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
            [json_encode(["type" => "json"], JSON_PRETTY_PRINT), json_encode(["type" => "json", "rows" => 2], JSON_PRETTY_PRINT), json_encode(["type" => "json"], JSON_PRETTY_PRINT)],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'double';
        $expected =<<<TXT
        ╔════════════════════╦═════════════════════╦════════════════════╗
        ║ header zero        ║     header one      ║         header two ║
        ╠════════════════════╬═════════════════════╬════════════════════╣
        ║ cola 0 左边        ║  cola 1 가운데 열   ║      cola 2 右の列 ║
        ║ colb 0 🤖          ║      colb 1 🤖      ║          colb 2 🤖 ║
        ║ {                  ║          {          ║                  { ║
        ║     "type": "json" ║     "type": "json", ║     "type": "json" ║
        ║ }                  ║        "rows": 2    ║                  } ║
        ║                    ║          }          ║                    ║
        ╚════════════════════╩═════════════════════╩════════════════════╝
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];


        // data 14
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0 左边', 'cola 1 가운데 열', 'cola 2 右の列'],
            ['colb 0 🤖', 'colb 1 🤖', 'colb 2 🤖'],
            ["name:\tfoo".PHP_EOL."descrt:\tbar", "colc 1", "colc 2"],
        ];
        $lefts = [0];
        $rights = [2];
        $centres = [1];
        $style = 'double';
        $expected =<<<TXT
        ╔═════════════╦══════════════════╦═══════════════╗
        ║ header zero ║    header one    ║    header two ║
        ╠═════════════╬══════════════════╬═══════════════╣
        ║ cola 0 左边 ║ cola 1 가운데 열 ║ cola 2 右の列 ║
        ║ colb 0 🤖   ║    colb 1 🤖     ║     colb 2 🤖 ║
        ║ name:   foo ║      colc 1      ║        colc 2 ║
        ║ descrt: bar ║                  ║               ║
        ╚═════════════╩══════════════════╩═══════════════╝
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];

        // data 15
        $headers = ['header zero', 'header one', 'header two'];
        $data = [
            ['cola 0', "{$red}cola 1{$close}", 'cola 2'],
            ['colb 0', 'colb 1', 'colb 2'],
        ];
        $lefts = [0, 1, 2];
        $rights = [];
        $centres = [];
        $style = 'standard';
        $expected =<<<TXT
        +-------------+------------+------------+
        | header zero | header one | header two |
        +-------------+------------+------------+
        | cola 0      | {$red}cola 1{$close}     | cola 2     |
        | colb 0      | colb 1     | colb 2     |
        +-------------+------------+------------+
        TXT;
        $provided[] = [$headers, $data, $expected, $style, $lefts, $rights, $centres];


        return $provided;
    }
}