<?php
namespace Gbhorwood\Macrame;

/**
 * ANSI: Convenience defines
 */
if(!defined('ESC')) define('ESC', "\033");

/**
 * ANSI: Cursor control
 */
if(!defined('LINE_UP')) define('LINE_UP', ESC."[F");
if(!defined('LINE_ERASE')) define('LINE_ERASE', ESC."[2K");
if(!defined('LINE_ERASE_TO_END')) define('LINE_ERASE_TO_END', ESC."[0K");
if(!defined('BACKSPACE')) define('BACKSPACE', chr(8));


/**
 * Handle input and output
 *
 */
class MacrameIO {

    /**
     * Writes $string to STDOUT
     *
     * @param  ?String $string The string to write to STDOUT
     * @return void
     */
    public static function writeStdout(?String $string):void
    {
        fwrite(self::outStream('out'), $string);
    }

    /**
     * Writes $string to STDERR
     *
     * @param  ?String $string The string to write to STDERR
     * @return void
     */
    public static function writeStderr(?String $string):void
    {
        fwrite(self::outStream('error'), $string);
    }

    /**
     * Erase the last line output
     *
     * @return void
     */
    public static function eraseLine():void
    {
        self::writeStdout(LINE_UP.LINE_ERASE);
    }

    /**
     * Erase $count lines and move cursor up
     *
     * @return void
     */
    public static function eraseLines(Int $count):void
    {
        for ($i = 0;$i<$count;$i++) {
            self::writeStdout(LINE_UP.LINE_ERASE);
        }
    }

    /**
     * Move character back one space on current line as <BACKSPACE>
     *
     * @return void
     */
    public static function backspace():void
    {
        self::writeStdout(BACKSPACE);
    }

    /**
     * Delete all output on current line from cursor position to end of line
     *
     * @return void
     */
    public static function eraseToEndOfLine():void
    {
        self::writeStdout(LINE_ERASE_TO_END);
    }

    /**
     * Read exactly one keystroke and return 
     *
     * @return String
     */
    public static function keyStroke():String
    {
        $c = null;
        readline_callback_handler_install('', function () { });
        while (true) {
            $r = array(STDIN);
            $w = null;
            $e = null;
            $n = stream_select($r, $w, $e, null);
            if ($n && in_array(STDIN, $r)) {
                $c = stream_get_contents(STDIN, 1);
                break;
            }
        }
        return $c;
    }

    /**
     * Output a newline
     *
     * @return void
     */
    public static function newline():void
    {
        self::writeStdout(PHP_EOL);
    }

    /**
     * Returns the number of cols to wrap output on.
     * This is 75% of the total columns to a lower bound of 80
     * or the full col width of the terminal if less than 80.
     *
     * @return Int
     * @note On systems without stty, this returns 80.
     */
    public static function getColWidth():int
    {
        // poll stty for the width and height of the terminal, discarding errors
        $ph = popen("/usr/bin/env stty size 2> /dev/null", 'r');
        $size = fread($ph, 9);
        pclose($ph);
        $sizeArray = explode(' ', $size);

        // bad return data probably means no stty. return 80.
        if(count($sizeArray) != 2) {
            return 80;
        }
        $columns = $sizeArray[1];
        if(filter_var($columns, FILTER_VALIDATE_INT) === false) {
            return 80;
        }

        // if terminal is less than 80, use full width
        if($columns < 80) {
            return (int)$columns;
        }

        // return 75% of terminal width or 80, whichever is higher
        $columns = (int)$columns;
        return $columns*.75 > 80 ? (int)floor($columns*.75) : 80;
    }

    /**
     * Gets the number of rows in the current terminal.
     *
     * @return Int
     */
    public static function getRowHeight():int
    {
        // poll stty for the width and height of the terminal, discarding errors
        $ph = popen("/usr/bin/env stty size 2> /dev/null", 'r');
        $size = fread($ph, 9);
        pclose($ph);
        $sizeArray = explode(' ', $size);

        // bad return data probably means no stty. return 25.
        if(count($sizeArray) != 2) {
            return 25;
        }
        $rows = $sizeArray[0];
        if(filter_var($rows, FILTER_VALIDATE_INT) === false) {
            return 25;
        }

        return (int)$rows;
    }

    /**
     * Tests if there is content on STDIN from pipe
     *
     * @return bool True if piped content exists.
     */
    public static function pipedContentExists():bool
    {
        $streams = [STDIN];
        $write_array = [];
        $except_array = [];
        $seconds = 0;
        return (bool)@stream_select($streams, $write_array, $except_array, $seconds);
    }

    /**
     * Returns content read from STDIN
     *
     * @return ?String The piped content, if any
     */
    public static function getPipedContent():?String
    {
        $pipedContent = null;
        if(self::pipedContentExists()) {
            while ($line = fgets(STDIN)) {
                $pipedContent .= $line;
            }
        }

        return $pipedContent;
    }

    /**
     * Yields content read from STDIN by line
     *
     * @return \Iterator
     */
    public static function getPipedContentGenerator():\Iterator
    {
        if(!self::pipedContentExists()) {
            //yield;
            return [];
        }
        else {
            while ($line = fgets(STDIN)) {
                yield rtrim($line, PHP_EOL);
            }
        }
    }


    /**
     * Returns the stream to write to for output based on argument $stream.
     * $stream 'out' for STDOUT, $stream 'error' for 'STDERR'. Default is STDOUT.
     *
     * If phpunit has set the global variable TESTENVIRONMENT to true, all stream
     * returns are the output buffer. This is to allow output buffering in tests
     * using phpunit's wrappers of the ob functions.
     *
     * @param  String $stream
     */
    private static function outStream(String $stream) // @phpstan-ignore-line
    {
        if(getenv('TESTENVIRONMENT')) {
            return fopen("php://output", "w");
        }

        if(strtolower($stream) == 'error') {
            return STDERR;
        }

        return STDOUT;
    }
}
