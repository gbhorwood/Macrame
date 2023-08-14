<?php
namespace Gbhorwood\Macrame;

/**
 * ANSI: Convenience defines of meta characters
 */
define('BACKSPACE', chr(8));
define('ESC', "\033"); // for use with ANSI codes
define('ERASE_TO_END_OF_LINE', "\033[0K");

/**
 * ANSI: styles
 */
define('CLOSE_ANSI', ESC."[0m");
define('BOLD_ANSI', ESC."[1m");
define('ITALIC_ANSI', ESC."[3m");
define('UNDERLINE_ANSI', ESC."[4m");
define('STRIKETHROUGH_ANSI', ESC."[9m");

/**
 * ANSI: colours
 */
define('BLACK_ANSI', ESC."[30m");
define('RED_ANSI', ESC."[31m");
define('GREEN_ANSI', ESC."[32m");
define('YELLOW_ANSI', ESC."[33m");
define('BLUE_ANSI', ESC."[34m");
define('MAGENTA_ANSI', ESC."[35m");
define('CYAN_ANSI', ESC."[36m");
define('WHITE_ANSI', ESC."[37m");

/**
 * ANSI: background colours
 */
define('BACKGROUND_BLACK_ANSI', ESC."[40m");
define('BACKGROUND_RED_ANSI', ESC."[41m");
define('BACKGROUND_GREEN_ANSI', ESC."[42m");
define('BACKGROUND_YELLOW_ANSI', ESC."[43m");
define('BACKGROUND_BLUE_ANSI', ESC."[44m");
define('BACKGROUND_MAGENTA_ANSI', ESC."[45m");
define('BACKGROUND_CYAN_ANSI', ESC."[46m");
define('BACKGROUND_WHITE_ANSI', ESC."[47m");

/**
 * Alignment definitions
 */
if(!defined('LEFT')) define('LEFT', 0);
if(!defined('CENTRE')) define('CENTRE', 2);
if(!defined('RIGHT')) define('RIGHT', 1);

/**
 * Handle creation and output of styled text.
 *
 */
class MacrameText {

    /**
     * ANSI: named styles mapped to ANSI codes
     * @var Array<String, String>
     * @access Private
     */
    private Array $ansiStyles = [
        'bold' => BOLD_ANSI,
        'italic' => ITALIC_ANSI, // terminal support limited. ymmv.
        'underline' => UNDERLINE_ANSI,
        'strike' => STRIKETHROUGH_ANSI,
        'strikethrough' => STRIKETHROUGH_ANSI,
    ];

    /**
     * ANSI: named colours mapped to ANSI codes
     * @var Array<String, String>
     * @access Private
     */
    private $ansiColours = [
        'black' => BLACK_ANSI,
        'red' => RED_ANSI,
        'green' => GREEN_ANSI,
        'yellow' => YELLOW_ANSI,
        'blue' => BLUE_ANSI,
        'magenta' => MAGENTA_ANSI,
        'cyan' => CYAN_ANSI,
        'white' => WHITE_ANSI,
    ];

    /**
     * ANSI: named background colours mapped to ANSI codes
     * @var Array<String, String>
     * @access Private
     */
    private $ansiBackgroundColours = [
        'black' => BACKGROUND_BLACK_ANSI,
        'red' => BACKGROUND_RED_ANSI,
        'green' => BACKGROUND_GREEN_ANSI,
        'yellow' => BACKGROUND_YELLOW_ANSI,
        'blue' => BACKGROUND_BLUE_ANSI,
        'magenta' => BACKGROUND_MAGENTA_ANSI,
        'cyan' => BACKGROUND_CYAN_ANSI,
        'white' => BACKGROUND_WHITE_ANSI,
    ];

    /**
     * ANSI: markup tags mapped to ANSI codes
     * @var Array<String, String>
     * @access Private
     */
    private $ansiTags = [
        '{\<\!CLOSE\!\>}' => CLOSE_ANSI,
        '{\<\!close\!\>}' => CLOSE_ANSI,
        '{\<\!BLACK\!\>}' => BLACK_ANSI,
        '{\<\!black\!\>}' => BLACK_ANSI,
        '{\<\!RED\!\>}' => RED_ANSI,
        '{\<\!red\!\>}' => RED_ANSI,
        '{\<\!GREEN\!\>}' => GREEN_ANSI,
        '{\<\!green\!\>}' => GREEN_ANSI,
        '{\<\!YELLOW\!\>}' => YELLOW_ANSI,
        '{\<\!yellow\!\>}' => YELLOW_ANSI,
        '{\<\!BLUE\!\>}' => BLUE_ANSI,
        '{\<\!blue\!\>}' => BLUE_ANSI,
        '{\<\!MAGENTA\!\>}' => MAGENTA_ANSI,
        '{\<\!magenta\!\>}' => MAGENTA_ANSI,
        '{\<\!CYAN\!\>}' => CYAN_ANSI,
        '{\<\!cyan\!\>}' => CYAN_ANSI,
        '{\<\!WHITE\!\>}' => WHITE_ANSI,
        '{\<\!white\!\>}' => WHITE_ANSI,
        '{\<\!BACKGROUND_BLACK\!\>}' => BACKGROUND_BLACK_ANSI,
        '{\<\!background_black\!\>}' => BACKGROUND_BLACK_ANSI,
        '{\<\!BACKGROUND_RED\!\>}' => BACKGROUND_RED_ANSI,
        '{\<\!background_red\!\>}' => BACKGROUND_RED_ANSI,
        '{\<\!BACKGROUND_GREEN\!\>}' => BACKGROUND_GREEN_ANSI,
        '{\<\!background_green\!\>}' => BACKGROUND_GREEN_ANSI,
        '{\<\!BACKGROUND_YELLOW\!\>}' => BACKGROUND_YELLOW_ANSI,
        '{\<\!background_yellow\!\>}' => BACKGROUND_YELLOW_ANSI,
        '{\<\!BACKGROUND_BLUE\!\>}' => BACKGROUND_BLUE_ANSI,
        '{\<\!background_blue\!\>}' => BACKGROUND_BLUE_ANSI,
        '{\<\!BACKGROUND_MAGENTA\!\>}' => BACKGROUND_MAGENTA_ANSI,
        '{\<\!background_magenta\!\>}' => BACKGROUND_MAGENTA_ANSI,
        '{\<\!BACKGROUND_CYAN\!\>}' => BACKGROUND_CYAN_ANSI,
        '{\<\!background_cyan\!\>}' => BACKGROUND_CYAN_ANSI,
        '{\<\!BACKGROUND_WHITE\!\>}' => BACKGROUND_WHITE_ANSI,
        '{\<\!background_white\!\>}' => BACKGROUND_WHITE_ANSI,
        '{\<\!BOLD\!\>}' => BOLD_ANSI,
        '{\<\!bold\!\>}' => BOLD_ANSI,
        '{\<\!ITALIC\!\>}' => ITALIC_ANSI,
        '{\<\!italic\!\>}' => ITALIC_ANSI,
        '{\<\!UNDERLINE\!\>}' => UNDERLINE_ANSI,
        '{\<\!underline\!\>}' => UNDERLINE_ANSI,
        '{\<\!STRIKE\!\>}' => STRIKETHROUGH_ANSI,
        '{\<\!strike\!\>}' => STRIKETHROUGH_ANSI,
    ];

    /**
     * Display tags for output levels, ie [OK]
     * RFC 5424 6.2.1
     * @var Array<String, Array<String>>
     * @access Private
     */
    private $levelOutputs = [
        'normal' => [
            'ok' => '['.GREEN_ANSI.BOLD_ANSI.'OK'.CLOSE_ANSI.']',  // non-standard
            'debug' => '['.GREEN_ANSI.BOLD_ANSI.'DEBUG'.CLOSE_ANSI.']', 
            'info' => '['.GREEN_ANSI.BOLD_ANSI.'INFO'.CLOSE_ANSI.']', 
            'notice' => '['.GREEN_ANSI.BOLD_ANSI.'NOTICE'.CLOSE_ANSI.']', 
            'warning' => '['.YELLOW_ANSI.BOLD_ANSI.'WARNING'.CLOSE_ANSI.']', 
            'error' => '['.RED_ANSI.BOLD_ANSI.'ERROR'.CLOSE_ANSI.']', 
            'critical' => '['.RED_ANSI.BOLD_ANSI.'CRITICAL'.CLOSE_ANSI.']', 
            'alert' => '['.YELLOW_ANSI.BOLD_ANSI.'ALERT'.CLOSE_ANSI.']', 
            'emergency' => '['.RED_ANSI.BOLD_ANSI.'EMERGENCY'.CLOSE_ANSI.']', 
        ],
        'reverse' => [
            'ok' => BACKGROUND_GREEN_ANSI.WHITE_ANSI.BOLD_ANSI.'[OK]'.CLOSE_ANSI,  // non-standard
            'debug' => BACKGROUND_GREEN_ANSI.WHITE_ANSI.BOLD_ANSI.'[DEBUG]'.CLOSE_ANSI, 
            'info' => BACKGROUND_GREEN_ANSI.WHITE_ANSI.BOLD_ANSI.'[INFO]'.CLOSE_ANSI, 
            'notice' => BACKGROUND_GREEN_ANSI.WHITE_ANSI.BOLD_ANSI.'[NOTICE]'.CLOSE_ANSI, 
            'warning' => BACKGROUND_YELLOW_ANSI.WHITE_ANSI.BOLD_ANSI.'[WARNING]'.CLOSE_ANSI, 
            'error' => BACKGROUND_RED_ANSI.WHITE_ANSI.BOLD_ANSI.'[ERROR]'.CLOSE_ANSI, 
            'critical' => BACKGROUND_RED_ANSI.WHITE_ANSI.BOLD_ANSI.'[CRITICAL]'.CLOSE_ANSI, 
            'alert' => BACKGROUND_YELLOW_ANSI.WHITE_ANSI.BOLD_ANSI.'[ALERT]'.CLOSE_ANSI, 
            'emergency' => BACKGROUND_RED_ANSI.WHITE_ANSI.BOLD_ANSI.'[EMERGENCY]'.CLOSE_ANSI, 
        ],
    ];

    /**
     * The text to format and output
     * @var ?String
     * @access private
     */
    private ?String $text;

    /**
     * Array of ANSI formatting codes to apply to the text
     * @var Array<String>
     * @access private
     */
    private Array $formatting = [];

    /**
     * Alignment of text output. One of LEFT, RIGHT, CENTRE.
     * @var Int
     * @access private
     */
    private int $alignment = LEFT;

    /**
     * Whether to wrap output to terminal width
     * @var bool
     * @access private
     */
    private bool $wrap = false;

    /**
     * Constructor
     *
     * @param  ?String $text
     * @return void
     */
    public function __construct(?String $text=null) 
    {
        $this->text = $text;
    }

    /**
     * Set the text string of the object
     *
     * @param  ?String $text
     * @return MacrameText
     */
    public function text(?String $text=null):MacrameText
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Append to the text string of the object
     *
     * @param  String $text
     * @return MacrameText
     */
    public function append(String $text):MacrameText 
    {
        $this->text .= $text;
        return $this;
    }

    /**
     * Apply ANSI colour format to $text
     *
     * @param  String $colour The string name of the colour, ie. 'red'
     * @return MacrameText
     */
    public function colour(String $colour):MacrameText {
        $this->formatting[] = isset($this->ansiColours[$colour]) ? $this->ansiColours[$colour] : null;
        return $this;
    }

    /**
     * Alias of colour
     *
     * @param  String $colour The string name of the colour, ie. 'red'
     * @return MacrameText
     */
    public function color(String $colour):MacrameText {
        return $this->colour($colour);
    }

    /**
     * Apply ANSI background colour format to $text
     *
     * @param  String $colour The string name of the colour, ie. 'red'
     * @return MacrameText
     */
    public function backgroundColour(String $colour):MacrameText {
        $this->formatting[] = isset($this->ansiBackgroundColours[$colour]) ? $this->ansiBackgroundColours[$colour] : null;
        return $this;
    }

    /**
     * Alias of backgroundColour
     *
     * @param  String $colour The string name of the colour, ie. 'red'
     * @return MacrameText
     */
    public function backgroundColor(String $colour):MacrameText {
        return $this->backgroundColour($colour);
    }

    /**
     * Apply ANSI style format to $text
     *
     * @param  String[] ...$styles The strings of the styles to apply, ie 'bold', 'italic'
     * @return MacrameText
     */
    public function style(String ...$styles):MacrameText {
        array_map(fn($style) => $this->formatting[] = isset($this->ansiStyles[$style]) ? $this->ansiStyles[$style] : null, $styles);
        return $this;
    }

    /**
     * Set alignment to centre
     *
     * @return MacrameText
     */
    public function centre():MacrameText {
        $this->alignment = CENTRE;
        return $this;
    }

    /**
     * Alias of centre
     *
     * @return MacrameText
     */
    public function center():MacrameText {
        return $this->centre();
    }

    /**
     * Set alignment to right
     *
     * @return MacrameText
     */
    public function right():MacrameText {
        $this->alignment = RIGHT;
        return $this;
    }

    /**
     * Set alignment to left
     *
     * @return MacrameText
     */
    public function left():MacrameText
    {
        $this->alignment = LEFT;
        return $this;
    }

    /**
     * Set output to wrap to terminal width
     *
     * @return MacrameText
     */
    public function wrap():MacrameText
    {
        $this->wrap = true;
        return $this;
    }

    /**
     * Return $text with formatting
     *
     * @return ?String
     */
    public function get():?String
    {
        return $this->text ? $this->format() : null;
    }

    /**
     * Write text with formatting to standard output
     *
     * @param  bool $newline If true, append newline.
     * @return void
     */
    public function write(bool $newline = false):void
    {
        fwrite($this->stream('out'), $newline ? $this->format() . PHP_EOL : $this->format());
    }

    /**
     * Write text with formatting to standard error
     *
     * @param  bool $newline If true, append newline.
     * @return void
     */
    public function writeError(bool $newline = false):void
    {
        fwrite($this->stream('error'), $newline ? $this->format(). PHP_EOL : $this->format());
    }

    /**
     * Write text as pages, navigated by <SPACE> for page, <CR> for line
     * and 'q' for quit.
     *
     * @return void
     */
    public function page():void
    {
        // calculated height of page, space left for nav output
        $pageSize = $this->getRowHeight() - 2;

        // text to output as array of lines
        $linesArray = explode(PHP_EOL, $this->format());

        // count of the total text to output, ie. for calculating percent complete
        $initialLinesArrayCount = count($linesArray);

        /**
         * Output $count lines of array $linesArray and return
         * $linesArray minus outputted content.
         *
         * @param  Array $linesArray The array
         * @param  Int   $count The number of lines to output
         * @return Array
         */
        $output = function(Array $linesArray, Int $count):Array {
            $i = 0;
            while(count($linesArray) > 0 && $i < $count) {
                fwrite($this->stream('out'), array_shift($linesArray).PHP_EOL);
                $i++;
            }
            return $linesArray;
        };

        /**
         * Poll user for input on paging and return size of next page.
         * Returns the size of the next page to outpu, in lines. -1 means stop outputting.
         *
         * @param  Int $linesArrayCount
         * @return Int
         */
        $pollForPage = function(Int $linesArrayCount) use($initialLinesArrayCount, $pageSize):Int {
            // display the 'more' line with percentage complete of total text
            $percent = intval(100 - (($linesArrayCount/$initialLinesArrayCount) * 100));
            fwrite($this->stream('out'), "-- MORE ($percent%) --".PHP_EOL);

            // a function to erase the 'more' line
            $eraseLine = fn() => fwrite($this->stream('out'),  "\033[F\033[2K");

            // poll user for input until valid character detected
            while(true) {
                // poll input
                readline_callback_handler_install('', function() {});
                $keystroke = $this->readKeystroke();

                // <SPACE> - a full page
                if (ord($keystroke) == 32) {
                    readline_callback_handler_remove();
                    $eraseLine();
                    return $pageSize;
                }

                // <CR> - one line
                if (ord($keystroke) == 10) {
                    readline_callback_handler_remove();
                    $eraseLine();
                    return 1;
                }

                // q - quit outputting
                if (ord($keystroke) == 113) {
                    readline_callback_handler_remove();
                    $eraseLine();
                    return -1;
                }
            }
        };

        // output pages until no more pages or user quits
        while(count($linesArray) > 0) {
            // output $pageSize lines, return new linesArray
            $linesArray = $output($linesArray, $pageSize);

            // if there are more lines, poll user for paging choice
            if(count($linesArray)) {
                $pageSize = $pollForPage(count($linesArray));
            }

            // pageSize from pollForPage of -1 means quit
            if($pageSize == -1) {
                break;
            }
        }
    }

    /**
     * Output 'OK' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function ok(bool $reverse = false):void {
        $this->writeLevel('ok', $reverse, 'out');
    }

    /**
     * Output 'DEBUG' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function debug(bool $reverse = false):void {
        $this->writeLevel('debug', $reverse, 'out');
    }

    /**
     * Output 'INFO' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function info(bool $reverse = false):void {
        $this->writeLevel('info', $reverse, 'out');
    }

    /**
     * Output 'NOTICE' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function notice(bool $reverse = false):void {
        $this->writeLevel('notice', $reverse, 'out');
    }

    /**
     * Output 'WARNING' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function warning(bool $reverse = false):void {
        $this->writeLevel('warning', $reverse, 'out');
    }

    /**
     * Output 'ERROR' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function error(bool $reverse = false):void {
        $this->writeLevel('error', $reverse, 'error');
    }

    /**
     * Output 'CRITICAL' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function critical(bool $reverse = false):void {
        $this->writeLevel('critical', $reverse, 'error');
    }

    /**
     * Output 'ALERT' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function alert(bool $reverse = false):void {
        $this->writeLevel('alert', $reverse, 'out');
    }

    /**
     * Output 'EMERGENCY' message  
     *
     * @param  bool $reverse Print leading tag in reverse colours
     * @return void
     */
    public function emergency(bool $reverse = false):void {
        $this->writeLevel('emergency', $reverse, 'error');
    }

    /**
     * Apply all of the formatting to the $text data member in the order:
     *
     * @return ?String
     */
    private function format():?String{
        if(is_null($this->text)) {
            return null;
        }
        $text = $this->applyMarkup($this->text);
        $text = $this->applyStyles($text);
        $text = $this->wrap ? $this->applyAnsiWrapper($text, $this->getColWidth()) : $text;
        $text = $this->align($text, $this->alignment, $this->getColWidth());
        return $text;
    }

    /**
     * Accepts string with markup tags and returns string with
     * the applied ANSI formatting.
     *
     * @param  String $text
     * @return String The marked up text
     */
    public function applyMarkup(String $text):String
    {
        return preg_replace(array_keys($this->ansiTags), array_values($this->ansiTags), $text);
    }
    
    /**
     * Apply ANSI style and colour codes to $text and return
     *
     * @param  String $text
     * @access private
     * @return String
     */
    private function applyStyles(String $text):String {
        $formatting = join(array_filter($this->formatting));
        $formattingClose = count(array_filter($this->formatting)) ? CLOSE_ANSI : null;
        return $formatting.$text.$formattingClose;
    }

    /**
     * String width that handles invisible ANSI characters, backspaces and tabs.
     *
     * @param  String $text The line of text to count
     * @return Int
     */
    public function mb_strwidth_ansi(String $text):int
    {
        // replace tabs with spaces
        $text = preg_replace('/[\x09|\t]/', '    ', $text);

        // remove escape sequences. this is more general than is used for ansi chars.
        $text = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "", $text);

        // remove ^c, ^z, <DELETE>
        $text = preg_replace('/[\x03|\x1a|\x7f]/', "", $text);

        // count of backspace chars plus char backspaced over to remove from width
        $backspaceAdjustments = function(String $string):Int
        {
            $arr = array_reverse(mb_str_split($string));

            $delcount = 0;
            $delwidth = 0;
            foreach($arr as $c) {
                if($delcount > 0 && $c != "\x08") {
                    $delcount--;
                    $delwidth += 2;
                }
                if($c == "\x08") {
                    $delcount++;
                }
            }   
            return $delwidth;
        };
        $backspaces = $backspaceAdjustments($text);

        return mb_strwidth($text) - $backspaces;
    } 

    /**
     * Aligns all lines in a multi-line string either left, centre or right
     * as defined by $alignment.
     *
     * Assumes string is already left-aligned.
     *
     * @param  String $text The text to pad for alignment
     * @param  Int    $alignment The alignment as integer from constants LEFT 0, CENTRE 1, RIGHT 2
     * @param  Int    $colWidth The width of the display area in character columns
     * @return String The text padded to align
     * @see mb_strwidth_ansi()
     */
    private function align(String $text, int $alignment, int $colWidth):String
    {
        if($alignment == LEFT) {
            return $text;
        }

        // convert to text to array of lines
        $textArray = explode(PHP_EOL, $text);

        // function to pad one line to the alignment
        $padder = function($text) use($alignment, $colWidth) {
            $padAmount = (int)floor(($colWidth - $this->mb_strwidth_ansi($text))/$alignment);
            $pad = array_fill(0, $padAmount, ".");
            return join('', array_fill(0, $padAmount, ' ')).$text;
        };

        // pad each line in the array of lines
        $padded = array_map($padder, $textArray);

        // return as string
        return join(PHP_EOL, $padded);
    }

    /**
     * Wrap text ignoring ANSI escape codes.
     *
     * @param  String $text The text to wrap
     * @param  Int    $cols The number of columns to wrap on
     * @return String The wrapped text
     */
    public function applyAnsiWrapper(String $text, Int $cols):String
    {
        /**
         * Tail call to build an accumulator array of lines broken on last 
         * break character (ie. space or tab) before $cols length.
         *
         * @param  String $tail
         * @param  Array  $accumulator
         * @return Array
         */
        $wrapper = function(String $tail, Array $accumulator = []) use(&$wrapper, $cols):Array {
            // the list of characters we break lines on.
            $breakChars = [
                ' ',
                '\t',
            ];

            // avoid leading whitespace, ie if line breaks on two or more spaces
            $tail = trim($tail);
            $cols = $cols + 1;

            // handle last line
            if(strlen($tail) < $cols) {
                $accumulator[] = $tail;
                return $accumulator;
            }

            // count of how many display chars, ie not ANSI codes
            $wrapCounter = 0;

            // count of how many total chars, including ANSI codes
            $charCounter = 0;

            // how many chars to advance wrapCounter. either 1 (if we're counting visible chars) or 0 (if we're not)
            $increment = 1;

            // char position of last character the line can break one, ie. last space before max length of row
            $lastBreakPosition = 0;

            // handle empty lines
            if($tail[$charCounter] == PHP_EOL) {
                $lastBreakPosition = $charCounter;
            }

            // loop over the tail til we hit the last break chacter before the col length
            while($charCounter < strlen($tail)) {

                // if we have an ESC char, we stop counting towards wrapCounter
                if(ord($tail[$charCounter]) == 27) {
                    $increment = 0;
                }

                // note position of last candidate to break the line
                if(in_array($tail[$charCounter], $breakChars)) {
                    $lastBreakPosition = $charCounter;
                }

                // advance wrap counter if not iterating over escape code
                $wrapCounter = $wrapCounter + $increment;

                // if we have an 'm' after an ESC, we resume counting toward wrapCounter
                if($tail[$charCounter] == 'm' && !$increment) {
                    $increment = 1;
                }

                // wrapCounter has reached the wrap length, stop advancing
                if($wrapCounter == $cols) {
                    break;
                }

                // handle paragraph breaks, ie 2 or more PHP_EOL in a row
                if($tail[$charCounter] == PHP_EOL && $tail[$charCounter + 1] == PHP_EOL) {
                    // wrap on paragraph break
                    $lastBreakPosition = $charCounter;

                    // calculate and add head to accumulator
                    $accumulator[] = substr($tail, 0, $lastBreakPosition); 

                    // calculate tail
                    $tail = substr($tail, $lastBreakPosition);

                    // add blank line to accumulator for each PHP_EOL after the first
                    for($i=1;$i<strlen($tail);$i++) {
                        if($tail[$i] != PHP_EOL) {
                            break;
                        }
                        $accumulator[] = null;
                    }

                    // recurse
                    return $wrapper($tail, $accumulator);
                }

                $charCounter++;

                // if we get to the end of the tail without breaking on the wrap counter, wrap on end of file.
                if($charCounter == strlen($tail)) {
                    $lastBreakPosition = $charCounter;
                }
            }

            // calculate and add head to accumulator
            $accumulator[] = substr($tail, 0, $lastBreakPosition); 

            // calculate tail
            $tail = substr($tail, $lastBreakPosition);

            // recurse
            return $wrapper($tail, $accumulator);
        };

        // cast accumulator of wrap-length lines to string and return
        return join(PHP_EOL, $wrapper($text));
    }

    /**
     * Returns the number of cols to wrap output on.
     * This is 75% of the total columns to a lower bound of 80
     * or the full col width of the terminal if less than 80.
     *
     * @return Int
     * @note On systems without stty, this returns 80.
     */
    private function getColWidth():int
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
    public function getRowHeight():int
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
     * Write level message to console via stream.
     *
     * @param  String $level
     * @param  bool   $reverse Print leading tag in reverse colours
     * @param  String $stream  One of 'out' or 'error'
     */
    private function writeLevel(String $level, bool $reverse, $stream):void
    {
        fwrite($this->stream($stream), $this->levelOutputs[$reverse ? 'reverse' : 'normal'][$level].' '.$this->format().PHP_EOL);
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
    private function stream(String $stream) // @phpstan-ignore-line
    {
        if(getenv('TESTENVIRONMENT')) {
            return fopen("php://output", "w");
        }

        if(strtolower($stream) == 'error') {
            return STDERR;
        }

        return STDOUT;
    }

    /**
     * Reads one keystroke from STDIN and returns
     *
     * @return String The single keystroke
     */
    public function readKeystroke():String
    {
        return stream_get_contents(STDIN, 1);
    }
}
