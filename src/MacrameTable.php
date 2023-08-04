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
            $warning = new MacrameText('Table column mismatch at '.$file.' line '.$line.'. No table created.');
            $warning->warning();
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
            switch(@$this->alignments[$k]) {
                case CENTRE:
                    return join(array_fill(0, (int)floor(($pads[$k] - $contentLength) / 2), ' ')) .
                    $v .
                    join(array_fill(0, (int)ceil(($pads[$k] - $contentLength) / 2), ' ')) .' | ';

                case RIGHT:
                    return join(array_fill(0, $pads[$k] - $contentLength, ' ')).$v.' | ';

                default:
                    return $v.join(array_fill(0, $pads[$k] - $contentLength, ' ')).' | ';
            }
        };

        /**
         * Create the header line
         */
        $headLine = '| ' . join('', array_map($makePaddedLine, array_keys($this->headers), $this->headers)).PHP_EOL;

        /**
         * Create all the data lines
         */
        $dataLines = join(PHP_EOL, array_map(function(Array $d) use($makePaddedLine){ // @phpstan-ignore-line
            return '| ' . join('', array_map($makePaddedLine, array_keys($d), $d));
        }, $this->data)).PHP_EOL;

        /**
         * Create the barrier line, ie. the line between the headers and data
         */
        $barrier = '+'.join('', array_map(function($k, $v) use($pads){
            return join(array_fill(0, $pads[$k]+1, '-')).'-+';
        }, array_keys($this->headers), $this->headers)).PHP_EOL;

        /**
         * Return the MacrameText object for the table string
         */
        return new MacrameText($barrier.$headLine.$barrier.$dataLines.$barrier);
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
}
