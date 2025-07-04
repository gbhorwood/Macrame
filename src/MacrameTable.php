<?php

namespace Gbhorwood\Macrame;

/**
 * Alignment definitions
 */
if (!defined('LEFT')) {
    define('LEFT', 0);
}
if (!defined('CENTRE')) {
    define('CENTRE', 1);
}
if (!defined('CENTER')) {
    define('CENTER', 1);
}
if (!defined('RIGHT')) {
    define('RIGHT', 2);
}

/**
 * Border definitions
 */
if (!defined('TABLE_BORDER_STANDARD')) {
    define('TABLE_BORDER_STANDARD', 'standard');
}
if (!defined('TABLE_BORDER_SOLID')) {
    define('TABLE_BORDER_SOLID', 'solid');
}
if (!defined('TABLE_BORDER_DOUBLE')) {
    define('TABLE_BORDER_DOUBLE', 'double');
}

use Gbhorwood\Macrame\MacrameIO as IO;
use Gbhorwood\Tabletown\Table as Table;

/**
 * Handle creation and output of nice tables
 *
 */
class MacrameTable
{
    /**
     * Array of table headers
     * @var Array<String>
     * @access private
     */
    private array $headers;

    /**
     * Array of arrays for each line of table data
     * @var Array<String>
     * @access private
     */
    private array $data;

    /**
     * Array keyed by column position containing alignment for the column
     * @var Array<Int, Int>
     * @access private
     */
    private array $alignments = [];

    /**
     * Style of table
     * @var String
     * @access private
     */
    private String $style = TABLE_BORDER_STANDARD;

    /**
     * Constructor
     *
     * @param  Array<String> $headers
     * @param  Array<String> $data
     */
    public function __construct(array $headers, array $data)
    {
        $this->headers = $headers;
        $this->data = $data;

        /**
         * Validate column count of header and data rows are the same
         */
        if (!$this->validateColCount()) {
            $e = new \Exception();
            $t = $e->getTrace();
            $file = $t[1]['file'];
            $line = $t[1]['line'];
            $text = new MacrameText('Table column mismatch at '.$file.' line '.$line.'. No table created.');
            $text->warning();
        }

        $this->alignments = array_fill(0, count($headers), LEFT);
    }

    /**
     * Return the table as string
     *
     * @return ?String
     */
    public function get(): ?String
    {
        return $this->create()->get();
    }

    /**
     * Output table to STDOUT
     *
     * @return void
     */
    public function write()
    {
        $this->create()->write();
    }

    /**
     * Create the table and return as a MacrameText object
     *
     * @return MacrameText
     */
    public function create(): MacrameText
    {
        /**
         * Validate column count of header and data rows are the same
         */
        if (!$this->validateColCount()) {
            $e = new \Exception();
            $t = $e->getTrace();
            $file = $t[1]['file'];
            $line = $t[1]['line'];
            $text = new MacrameText('Table column mismatch at '.$file.' line '.$line.'. No table created.');
            $text->warning();
            return new MacrameText();
        }

        return new MacrameText(Table::get($this->headers, $this->data, $this->style, $this->alignments).PHP_EOL);
    }

    /**
     * Centre align the column defined by $key
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function centre(Int $key): MacrameTable
    {
        $this->alignments[$key] = CENTRE;
        return $this;
    }

    /**
     * Alias of centre()
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function center(Int $key): MacrameTable
    {
        return $this->centre($key);
    }

    /**
     * Right align the column defined by $key
     *
     * @param  Int $key The key of the table column, starting at zero
     * @return MacrameTable
     */
    public function right(Int $key): MacrameTable
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
    public function left(Int $key): MacrameTable
    {
        $this->alignments[$key] = LEFT;
        return $this;
    }

    /**
     * Set border style to 'solid'
     */
    public function solid(): MacrameTable
    {
        $this->style = TABLE_BORDER_SOLID;
        return $this;
    }

    /**
     * Set border style to 'double'
     */
    public function double(): MacrameTable
    {
        $this->style = TABLE_BORDER_DOUBLE;
        return $this;
    }

    /**
     * Set border style to 'standard'
     */
    public function standard(): MacrameTable
    {
        $this->style = TABLE_BORDER_STANDARD;
        return $this;
    }

    /**
     * Validate that the count of columns for the head and all rows of the data
     * are the same. False if not.
     *
     * @return bool
     */
    private function validateColCount(): bool
    {
        return count(array_unique(array_map(fn ($d) => count($d), array_merge($this->data, [$this->headers])))) == 1;
    }
}
