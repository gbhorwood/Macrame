<?php
namespace Gbhorwood\Macrame;


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
        fwrite(self::outStream('out'), "\033[F\033[2K");
    }

    /**
     * Move character back one space on current line as <BACKSPACE>
     *
     * @return void
     */
    public static function backspace():void
    {
        fwrite(self::outStream('out'), chr(8));
    }

    /**
     * Delete all output on current line from cursor position to end of line
     *
     * @return void
     */
    public static function eraseToEndOfLine():void
    {
        fwrite(self::outStream('out'), "\033[0K");
    }

    /**
     * Read exactly one keystroke and return 
     *
     * @return String
     */
    public static function keyStroke():String
    {
        return stream_get_contents(STDIN, 1);
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