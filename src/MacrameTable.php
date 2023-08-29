<?php
namespace Gbhorwood\Macrame;

/**
 * Alignment definitions
 */
if(!defined('LEFT')) define('LEFT', 0);
if(!defined('CENTRE')) define('CENTRE', 1);
if(!defined('RIGHT')) define('RIGHT', 2);

/**
 * Handle creation and output of nice tables
 *
 */
class MacrameTable {

    /**
     * Array of table headers
     * @var Array<String>
     * @access private
     */
    private Array $headers;

    /**
     * Array of arrays for each line of table data
     * @var Array<String>
     * @access private
     */
    private Array $data;

    /**
     * Array keyed by column position containing alignment for the column
     * @var Array<Int, Int>
     * @access private
     */
    private Array $alignments = [];

    /**
     * MacrameText object
     * @var MacrameText
     * @access private
     */
    private MacrameText $text;

    /**
     * Array of arrays defining table styles
     * @var Array<Array<String,Array<String,String>>>
     * @access private
     */
    private Array $tableStyles = [
        'standard' => [
            'default' => [
                'bar' => '|',
                'separator' => '-',
            ],
            'top' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
            'bottom' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
            'inner' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
        ],

        'solid' => [
            'default' => [
                'bar' => '│',
                'separator' => '─',
            ],
            'top' => [
                'left' => '┌',
                'right' => '┐',
                'join' => '┬',
            ],
            'bottom' => [
                'left' => '└',
                'right' => '┘',
                'join' => '┴',
            ],
            'inner' => [
                'left' => '├',
                'right' => '┤',
                'join' => '┼',
            ],
        ],

        'double' => [
            'default' => [
                'bar' => '║',
                'separator' => '═',
            ],
            'top' => [
                'left' => '╔',
                'right' => '╗',
                'join' => '╦',
            ],
            'bottom' => [
                'left' => '╚',
                'right' => '╝',
                'join' => '╩',
            ],
            'inner' => [
                'left' => '╠',
                'right' => '╣',
                'join' => '╬',
            ],
        ],
    ];

    /**
     * Style of table
     * @var String
     * @access private
     */
    private String $style = 'standard';

    /**
     * Constructor
     *
     * @param  Array<String> $headers
     * @param  Array<String> $data
     * @param  MacrameText   $text
     */
    public function __construct(Array $headers, Array $data, MacrameText $text)
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->text = $text;
    }

    /**
     * Write table with formatting to standard output
     *
     * @return void
     */
    public function write():void
    {
        $this->create()->write();
    }

    /**
     * Return table as text with formatting
     *
     * @return ?String
     */
    public function get():?String
    {
        return $this->create()->get();
    }

    /**
     * Centre align the column defined by $key
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function centre(Int $key):MacrameTable
    {
        $this->alignments[$key] = CENTRE;
        return $this;
    }

    /**
     * Right align the column defined by $key
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function right(Int $key):MacrameTable
    {
        $this->alignments[$key] = RIGHT;
        return $this;
    }

    /**
     * Left align the column defined by $key. Columns are left-aligned by default.
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function left(Int $key):MacrameTable
    {
        $this->alignments[$key] = LEFT;
        return $this;
    }

    /**
     * Create the table and return as a MacrameText object
     *
     * @return MacrameText
     */
    public function create():MacrameText
    {
        /**
         * Validate column count of header and data rows are the same
         */
        if(!$this->validateColCount()) {
            $e = new \Exception();
            $t = $e->getTrace();
            $file = $t[1]['file'];
            $line = $t[1]['line'];
            $this->text->text('Table column mismatch at '.$file.' line '.$line.'. No table created.')->warning();
            return new MacrameText();
        }

        /**
         * Create an array keyed by column position of the length longest lines in each column.
         * Used for padding.
         */
        $pads = [];
        for($i=0;$i<count($this->headers);$i++) {
            $pads[$i] = max(array_map(fn($data) => max($this->text->mb_strwidth_ansi($data[$i]), $this->text->mb_strwidth_ansi($this->headers[$i])), $this->data));
        }

        /**
         * Function to create one outputtable line, padded to fit.
         * Note: str_pad does not use ansi-safe string lengths.
         */
        $makePaddedLine = function(Int $k, String $v) use($pads):String {
            $contentLength = $this->text->mb_strwidth_ansi($v);
            $bar = $this->getStyle()['default']['bar'];
            switch(@$this->alignments[$k]) {
                case CENTRE:
                    return join(array_fill(0, (int)floor(($pads[$k] - $contentLength) / 2), ' ')) .
                    $v .
                    join(array_fill(0, (int)ceil(($pads[$k] - $contentLength) / 2), ' ')) ." $bar ";

                case RIGHT:
                    return join(array_fill(0, $pads[$k] - $contentLength, ' ')).$v." $bar ";

                default:
                    return $v.join(array_fill(0, $pads[$k] - $contentLength, ' '))." $bar ";
            }
        };

        /**
         * Create the header line
         */
        $headLine = trim($this->getStyle()['default']['bar'].' ' . join('', array_map($makePaddedLine, array_keys($this->headers), $this->headers))).PHP_EOL;

        /**
         * Create all the data lines
         */
        $dataLines = join(PHP_EOL, array_map(function(Array $d) use($makePaddedLine){ // @phpstan-ignore-line
            return trim($this->getStyle()['default']['bar'].' ' . join('', array_map($makePaddedLine, array_keys($d), $d)));
        }, $this->data)).PHP_EOL;

        /**
         * Function to return a string that acts as a table barrier line.
         *
         * @param  String $position One of 'tob', 'bottom' or 'inner'
         */
        $getLine = function(String $position) use($pads){
            $left = $this->getStyle()[$position]['left'];
            $separator = $this->getStyle()['default']['separator'];
            $right = $this->getStyle()[$position]['right'];
            $join = $this->getStyle()[$position]['join'];
            return mb_substr($left .
                    join('',
                        array_map(
                            function($k, $v) use($pads, $join, $separator) {
                                return join(array_fill(0, $pads[$k]+1, $separator)) .
                                    $separator .
                                    $join;
                            },
                            array_keys($this->headers),
                            $this->headers)
                    ), 0, -1).$right.PHP_EOL;
        };

        /**
         * Get the three classes of barrier lines
         */
        $topLine = $getLine('top');
        $bottomLine = $getLine('bottom');
        $innerLine = $getLine('inner');

        /**
         * Return the MacrameText object for the table string
         */
        return new MacrameText($topLine . $headLine . $innerLine . $dataLines . $bottomLine);
    }

    /**
     * Set border style to 'solid'
     */
    public function solid():MacrameTable
    {
        $this->style = 'solid';
        return $this;
    }

    /**
     * Set border style to 'double'
     */
    public function double():MacrameTable
    {
        $this->style = 'double';
        return $this;
    }

    /**
     * Set border style to 'standard'
     */
    public function standard():MacrameTable
    {
        $this->style = 'standard';
        return $this;
    }

    /**
     * Validate that the count of columns for the head and all rows of the data
     * are the same. False if not.
     *
     * @return bool
     */
    private function validateColCount():bool
    {
        return count(array_unique(array_map(fn($d) => count($d), array_merge($this->data, [$this->headers])))) == 1;
    }

    /**
     * Get the array defining the table style
     *
     * @return Array<String,Array<String,String>>
     */
    private function getStyle():Array
    {
        return $this->tableStyles[$this->style];
    }
}
