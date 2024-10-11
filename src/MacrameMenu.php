<?php
namespace Gbhorwood\Macrame;

use \Gbhorwood\Macrame\MacrameIO as IO;

/**
 * Key codes
 */
if(!defined('KEY_RETURN')) define('KEY_RETURN', 10);
if(!defined('KEY_UP_ARROW')) define('KEY_UP_ARROW', 65);
if(!defined('KEY_DOWN_ARROW')) define('KEY_DOWN_ARROW', 66);
if(!defined('KEY_RIGHT_ARROW')) define('KEY_RIGHT_ARROW', 67);
if(!defined('KEY_LEFT_ARROW')) define('KEY_LEFT_ARROW', 68);
if(!defined('KEY_TAB')) define('KEY_TAB', 9);
if(!defined('KEY_BACKSPACE')) define('KEY_BACKSPACE', 127);
if(!defined('KEY_DELETE')) define('KEY_DELETE', 126);
if(!defined('KEY_O')) define('KEY_O', 111);

/**
 * Alignment definitions
 */
if(!defined('LEFT')) define('LEFT', 0);
if(!defined('CENTRE')) define('CENTRE', 2);
if(!defined('RIGHT')) define('RIGHT', 1);

/**
 * Handle menus
 *
 */
class MacrameMenu
{
    /**
     * MacrameText object
     * @var MacrameText
     * @access private
     */
    private MacrameText $text;

    /**
     * Foreground colour of option
     * @var ?String
     * @access private
     */
    private ?String $colourOption;

    /**
     * Foreground colour of selected option
     * @var ?String
     * @access private
     */
    private ?String $colourSelected;

    /**
     * Styles for options
     * @var Array<String>
     * @access private
     */
    private Array $styleOption = [];

    /**
     * Styles for selected
     * @var Array<String>
     * @access private
     */
    private Array $styleSelected = [];

    /**
     * Alignment of options in menu. One of LEFT, RIGHT, CENTRE.
     * @var Int
     * @access private
     */
    private Int $optionAlignment = LEFT;

    /**
     * Alignment of menu. One of LEFT, RIGHT, CENTRE.
     * @var Int
     * @access private
     */
    private Int $menuAlignment = LEFT;

    /**
     * Erase menu when done
     * @var Bool
     * @access private
     */
    private bool $erase = false;

    /**
     * Memoization
     */

    /**
     * Displayable header. For performance.
     * @var ?MacrameText
     * @access private
     */
    private ?MacrameText $headerPadded = null;

    /**
     * Count of header and option rows. For performance.
     * @var ?Int
     * @access private
     */
    private ?Int $rowCount = null;

    /**
     * Displayable options. For performance.
     * @var Array<Array<MacrameText>>
     * @access private
     */
    private Array $options = [];

    /**
     * Constructor
     *
     * @param  MacrameText   $text
     */
    public function __construct(MacrameText $text)
    {
        $this->text = $text;
    }

    /**
     * Set alignment of options to left
     *
     * @return MacrameMenu
     */
    public function optionLeft():MacrameMenu
    {
        $this->optionAlignment = LEFT;
        return $this;
    }

    /**
     * Set alignment of options to right
     *
     * @return MacrameMenu
     */
    public function optionRight():MacrameMenu
    {
        $this->optionAlignment = RIGHT;
        return $this;
    }

    /**
     * Set alignment of options to centre
     *
     * @return MacrameMenu
     */
    public function optionCentre():MacrameMenu
    {
        $this->optionAlignment = CENTRE;
        return $this;
    }

    /**
     * Alias of optionCentre()
     *
     * @return MacrameMenu
     */
    public function optionCenter():MacrameMenu
    {
        return $this->optionCentre();
    }

    /**
     * Set alignment of menu to left
     *
     * @return MacrameMenu
     */
    public function menuLeft():MacrameMenu
    {
        $this->menuAlignment = LEFT;
        return $this;
    }

    /**
     * Set alignment of menu to right
     *
     * @return MacrameMenu
     */
    public function menuRight():MacrameMenu
    {
        $this->menuAlignment = RIGHT;
        return $this;
    }

    /**
     * Set alignment of menu to centre
     *
     * @return MacrameMenu
     */
    public function menuCentre():MacrameMenu
    {
        $this->menuAlignment = CENTRE;
        return $this;
    }

    /**
     * Alias of menuCentre()
     *
     * @return MacrameMenu
     */
    public function menuCenter():MacrameMenu
    {
        return $this->menuCentre();
    }

    /**
     * Set foreground colour of options of menu
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colourOption(String $colour):MacrameMenu
    {
        $this->colourOption = $colour;
        return $this;
    }

    /**
     * Alias of colourOption
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colorOption(String $colour):MacrameMenu
    {
        return $this->colourOption($colour);
    }

    /**
     * Set foreground colour of the selected option of menu
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colourSelected(String $colour):MacrameMenu
    {
        $this->colourSelected = $colour;
        return $this;
    }

    /**
     * Alias of colourSelected
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colorSelected(String $colour):MacrameMenu
    {
        return $this->colourSelected($colour);
    }

    /**
     * Set style of options of menu
     *
     * @param  String $style The style as defined in MacrameText
     * @return MacrameMenu
     */
    public function styleOption(String $style):MacrameMenu
    {
        $this->styleOption[] = $style;
        return $this;
    }

    /**
     * Set style of the selected option of menu
     *
     * @param  String $style The style as defined in MacrameText
     * @return MacrameMenu
     */
    public function styleSelected(String $style):MacrameMenu
    {
        $this->styleSelected[] = $style;
        return $this;
    }

    /**
     * Set whether to erase menu output after menu completed. Toggle
     *
     * @return MacrameMenu
     */
    public function erase():MacrameMenu
    {
        $this->erase = !$this->erase;
        return $this;
    }

    /**
     * Execute an interactive menu and return the selected option
     *
     * @param  Array<String> $options The array of options in the menu
     * @param  ?String       $header  The optional header to show
     * @return String  The selected option string
     */
    public function interactive(Array $options, ?String $header = null):String
    {
        IO::hideCursor();

        // strip all macrame formatting tags from the string
        $optionsSanitized = array_map(fn($t) => $this->text->stripFormatting($t), $options);

        // initial selected element is the first
        $selectedIndex = 0;

        // output menu
        $this->printInteractiveMenu($optionsSanitized, $selectedIndex, $header, true);

        // poll for user input
        while(true) {
            $key = IO::keyStroke();

            // handle user input
            switch(ord($key)) {

                // down menu
                case KEY_DOWN_ARROW:
                case KEY_TAB:
                    $selectedIndex = $selectedIndex >= count($optionsSanitized) - 1 ? 0 : $selectedIndex + 1; // rollover to top
                    $this->printInteractiveMenu($optionsSanitized, $selectedIndex, $header);
                    break;

                // up menu
                case KEY_UP_ARROW:
                    $selectedIndex = $selectedIndex <= 0 ? count($optionsSanitized) - 1 : $selectedIndex - 1; // rollover to bottom
                    $this->printInteractiveMenu($optionsSanitized, $selectedIndex, $header);
                    break;

                // select item
                case KEY_RETURN:
                    IO::showCursor();
                    if($this->erase) {
                        IO::eraseLines($this->rowCount);
                    }
                    return $optionsSanitized[$selectedIndex];

                // all other keys leader functionality
                default:
                    for($i=0; $i<count($optionsSanitized);$i++) {
                        if(str_starts_with(strtolower($optionsSanitized[$i]), strtolower($key))) {
                            $selectedIndex = $i;
                            break;
                        }
                    }
                    $this->printInteractiveMenu($optionsSanitized, $selectedIndex, $header);
                    break;
            }
        }
    }

    /**
     * Execute a date picker menu and return the selected date
     *
     * @param  String  $date The starting date in any valid date format
     * @param  ?String $header  The optional header to show
     * @return String  The selected date in format Y-m-d
     */
    public function datePicker(String $date, ?String $header = null):String
    {
        IO::hideCursor();

        $index = 0;
        $leaderString = '';

        /**
         * Validate date
         */
        try {
            $dateObj = new \DateTime($date);
        }
        catch(\Exception $e) {
            $error = new MacrameText("Provided string '$date' is not a valid date");
            $error->error();
            return $date;
        }

        /**
         * Function to output date as horizontal menu
         *
         * @param  DateTime $dateObj The DateTime object
         * @param  Int      $index
         * @return void
         */
        $display = function(\DateTime $dateObj, Int $index) use($header) {
            $parts[0] = $dateObj->format('Y');
            $parts[1] = $dateObj->format('M');
            $parts[2] = $dateObj->format('d');
            $this->printHorizontalMenu($parts, $index, $header);
        };

        /**
         * Function to increment/decrement the date object by one unit. ie. on arrow up or down.
         *
         * @param  DateTime $dateObj   The DateTime object
         * @param  Int      $index     The field in the menu that maps to the date part. ie. 0 for year, 1 for month and 2 for day.
         * @param  String   $increment The amount of increment/decrement for DateTime's modify(), ie. '+1'
         * @return DateTime
         */
        $update = function(\DateTime $dateObj, Int $index, String $increment):\DateTime {
            $fields = ['year', 'month', 'day'];
            $dateObj->modify($increment.' '.$fields[$index]);
            return $dateObj;
        };

        /**
         * Function to handle leader keys. ie. if the user presses 'n' and then 'o' when the index
         * is on the month field, set date object month to 'nov'.
         * 
         * @param  DateTime $dateObj   	  The DateTime object
         * @param  Int      $index        The field in the menu that maps to the date part. ie. 0 for year, 1 for month and 2 for day.
         * @param  String   $leaderString The string of leader keys pressed by the user
         * @return Array    First element is $dateObj, second element is updated $leaderString
         */
        $handleLeaderKeys = function(\DateTime $dateObj, Int $index, String $leaderString) {
            $parts[0] = $dateObj->format('Y');
            $parts[1] = $dateObj->format('m');
            $parts[2] = $dateObj->format('d');

            switch ($index) {
                /**
                 * Year
                 */
                case 0;
                    $leaderString = strlen($leaderString) > 4 ? substr($leaderString, -1) : $leaderString;
                    $y = str_pad($leaderString, 4, '0');
                    return [
                        new \DateTime($y.$parts[1].$parts[2]),
                        $leaderString,
                    ];

                /**
                 * Month
                 */
                case 1;
                    $leaderString = strlen($leaderString) > 3 ? substr($leaderString, -1) : $leaderString;
                    $monthNumber = $parts[1];
                    $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                    $valid = false;
                    foreach($months as $monthIndex => $month) {
                        if(preg_match("/^$leaderString/", $month)) {
                            $monthNumber = str_pad((string)($monthIndex + 1), 2, '0', STR_PAD_LEFT);
                            $valid = true;
                            break;
                        }
                    }
                    return [
                        new \DateTime($parts[0].$monthNumber.$parts[2]),
                        $valid ? $leaderString : '',
                    ];
                    
                /**
                 * Day
                 */
                case 2;
                    $leaderString = strlen($leaderString) > 2 ? substr($leaderString, -1) : $leaderString;
                    $d = substr(str_pad($leaderString, 2, '0', STR_PAD_LEFT), -2);
                    return [
                        new \DateTime($parts[0].$parts[1].$d),
                        $leaderString,
                    ];
            }
        };

        $display($dateObj, $index);

        // poll for user input
        while(true) {
            $key = IO::keyStroke();

            // handle user input
            switch(ord($key)) {

                // right menu
                case KEY_RIGHT_ARROW:
                case KEY_TAB:
                    $leaderString = '';
                    $index = $index >= 2 ? 0 : $index + 1; // rollover to start
                    $display($dateObj, $index);
                    break;

                // left menu
                case KEY_LEFT_ARROW:
                    $leaderString = '';
                    $index = $index <= 0 ? 2 : $index - 1; // rollover to end
                    $display($dateObj, $index);
                    break;

                // increment date field
                case KEY_DOWN_ARROW:
                    $leaderString = '';
                    $dateObj = $update($dateObj, $index, '+1');
                    $display($dateObj, $index);
                    break;

                // decrement date field
                case KEY_UP_ARROW:
                    $leaderString = '';
                    $dateObj = $update($dateObj, $index, '-1');
                    $display($dateObj, $index);
                    break;

                // leader key pressed
                case ctype_alnum($key):
                    $leaderString .= $key;
                    $result = $handleLeaderKeys($dateObj, $index, $leaderString);
                    $dateObj = $result[0];
                    $leaderString = $result[1];
                    $display($dateObj, $index);
                    break;

                // leader key deleted
                case KEY_BACKSPACE:
                    $leaderString = substr($leaderString, 0, -1);
                    $result = $handleLeaderKeys($dateObj, $index, $leaderString);
                    $dateObj = $result[0];
                    $leaderString = $result[1];
                    $display($dateObj, $index);
                    break;

                // date selected
                case KEY_RETURN:
                    IO::showCursor();
                    return (string)$dateObj->format('Y-m-d');
            }
        }
    }

    public function horizontal(Array $options, ?String $header = null)
    {
        IO::hideCursor();

        // strip all macrame formatting tags from the string
        $optionsSanitized = array_map(fn($t) => $this->text->stripFormatting($t), $options);

        // initial selected element is the first
        $selectedIndex = 0;

        // output menu
        $this->printHorizontalMenu($optionsSanitized, $selectedIndex, $header, true);

        // poll for user input
        while(true) {
            $key = IO::keyStroke();

            // handle user input
            switch(ord($key)) {

                // next menu item
                case KEY_DOWN_ARROW:
                case KEY_RIGHT_ARROW:
                case KEY_TAB:
                    $selectedIndex = $selectedIndex >= count($optionsSanitized) - 1 ? 0 : $selectedIndex + 1; // rollover to top
                    $this->printHorizontalMenu($optionsSanitized, $selectedIndex, $header);
                    break;

                // previous menu item
                case KEY_UP_ARROW:
                case KEY_LEFT_ARROW:
                    $selectedIndex = $selectedIndex <= 0 ? count($optionsSanitized) - 1 : $selectedIndex - 1; // rollover to bottom
                    $this->printHorizontalMenu($optionsSanitized, $selectedIndex, $header);
                    break;

                // select item
                case KEY_RETURN:
                    IO::showCursor();
                    return $options[$selectedIndex];

                // all other keys
                default:
                    for($i=0; $i<count($optionsSanitized);$i++) {
                        if(str_starts_with(strtolower($optionsSanitized[$i]), strtolower($key))) {
                            $selectedIndex = $i;
                            break;
                        }
                    }
                    $this->printHorizontalMenu($optionsSanitized, $selectedIndex, $header);
                    break;
            }
        }
    }

    /**
     * Prints a horizontal interactive menu
     *
     * @param  Array<String>  $options
     * @param  Int $selected The index of the option to show as currentlys selected
     * @param  ?String $header The optional header
     * @param  Bool $initial True if first draw of menu. Suppresses erasing.
     * @return void
     */
    private function printHorizontalMenu(Array $options, Int $selected, ?String $header = null, Bool $initial = false):void
    {
        // erase menu and write header, if any
        if(!$initial) {
            IO::eraseLines(1);
        }

        // build menu options with styling as array
        $displayOptions = [];
        for($i=0;$i<count($options);$i++) {
            $text = new MacrameText($options[$i]);
            if($i == $selected) {
                if(isset($this->colourSelected)) {
                    $text->colour($this->colourSelected);
                }
                foreach($this->styleSelected as $style) {
                    $text->style($style);
                }
                if(count($this->styleSelected) == 0 && !isset($this->colourSelected)) {
                    $text->reverse();
                }
            }
            else {
                if(isset($this->colourOption)) {
                    $text->colour($this->colourOption);
                }

                foreach($this->styleOption as $style) {
                    $text->style($style);
                }
            }
            $displayOptions[] = $text->get();
        }

        /**
         * Build horizontal menu line with optional alignment
         */
        $menuLine = join(' ', $displayOptions);
        if($this->menuAlignment != LEFT) {
            // strip escape codes before calculating width
            $padAmount = (int)floor((IO::getColWidth() - $this->text->mb_strwidth_ansi($menuLine))/$this->menuAlignment);
            $menuLine = join(array_fill(0, $padAmount, " ")).$menuLine;

        }

        /**
         * Output header with optional alignment
         */
        if($header) {
            $headerText = new MacrameText($header);

            if(!$initial) {
                IO::eraseLines($headerText->rowCount());
            }

            // handle centre and right padding
            if($this->menuAlignment != LEFT) {
                $headerLines = array_map(function($headerLine) {
                    $padAmount = (int)floor((IO::getColWidth() - $this->text->mb_strwidth_ansi($headerLine))/$this->menuAlignment);
                    $headerPadded = join(array_fill(0, $padAmount, " ")).$headerLine;
                    return $headerPadded;
                }, explode(PHP_EOL, $headerText->wrap()->get()));
                $headerText = new MacrameText(join(PHP_EOL, $headerLines));
            }
            $headerText->write(true);
        }

        $output = new MacrameText($menuLine);
        $output->write(true);
    }

    /**
     * Prints an interactive menu
     *
     * @param  Array<String>  $options
     * @param  Int $selected The index of the option to show as currentlys selected
     * @param  ?String $header The optional header
     * @param  Bool $initial True if first draw of menu. Suppresses erasing.
     * @return void
     */
    private function printInteractiveMenu(Array $options, Int $selected, ?String $header = null, $initial = false):void
    {
        /**
         * Function to get the width of the longest line in the options for padding.
         * Handles multi-line options and options that require wrapping to console.
         *
         * @param  Array<String> $options
         * @return Int
         */
        $getMaxWidth = function($options) {
            $optionsRawLines = explode(PHP_EOL, join(PHP_EOL, array_map(function($t) {
                $ttext = new MacrameText($t);
                return $ttext->wrap()->get();
            }, $options)));
            return max(array_map(fn($t) => $this->text->mb_strwidth_ansi($t), $optionsRawLines));
        };

        /**
         * Get a string of spaces to right pad a given option string
         *
         * @param  String $text
         * @param  Int    $width
         * @return String
         */
        $getRightPad = function(String $text, Int $width):String {
            if($this->optionAlignment == RIGHT) {
                return '';
            }
            if($this->optionAlignment == CENTRE && $this->menuAlignment == LEFT) {
                $padAmount = (int)floor(($width - ($this->text->mb_strwidth_ansi($text)/$this->optionAlignment)));
                return join(array_fill(0, $padAmount, " "));
            }
            if($this->optionAlignment == CENTRE && $this->menuAlignment != LEFT) {
                $padAmount = (int)ceil(($width - $this->text->mb_strwidth_ansi($text)) / $this->optionAlignment);
                return join(array_fill(0, $padAmount, " "));
            }
            $padAmount = (int)floor(($width - $this->text->mb_strwidth_ansi($text)));
            return $padAmount > 0 ? join(array_fill(0, $padAmount, " ")) : "";
        };

        /**
         * Get a string of spaces to left pad a given option string
         *
         * @param  String $text
         * @param  Int    $width
         * @param  Int    $indent The number of spaces to indent if left-aligned. Default 0.
         * @return String
         */
        $getLeftPad = function(String $text, Int $width, Int $indent = 0):String {
            if($this->optionAlignment == RIGHT) {
                $padAmount = (int)floor(($width - $this->text->mb_strwidth_ansi($text)));
                return join(array_fill(0, $padAmount, " "));
            }
            if($this->optionAlignment == CENTRE) {
                $padAmount = (int)floor(($width - $this->text->mb_strwidth_ansi($text)) / 2);
                return join(array_fill(0, $padAmount, " "));
            }

            return join(array_fill(0, $indent, ' '));
        };

        $maxWidth = $getMaxWidth($options);

        /**
         * Get header Text object, padded for alignment
         *
         * @param  String $header
         * @return MacrameText
         */
        $getPaddedHeader = function(String $header) use($getRightPad, $getLeftPad, $maxWidth){
            // memoization for performance
            if($this->headerPadded) {
                return $this->headerPadded;
            }

            $headerText = new MacrameText($header);
            $headerLines = array_map(function($h) use($getRightPad, $getLeftPad, $maxWidth) {
                $headerPadded = new MacrameText($getLeftPad($h, $maxWidth).$h.$getRightPad($h, $maxWidth));
                switch($this->menuAlignment) {
                    case LEFT:
                        $headerPadded->left();
                        break;
                    case RIGHT:
                        $headerPadded->right();
                        break;
                    case CENTRE:
                        $headerPadded->centre();
                        break;
                }
                return $headerPadded->get();
            }, explode(PHP_EOL, $headerText->wrap()->get()));

            $headerPadded = new MacrameText(join(PHP_EOL, $headerLines));
            $this->headerPadded = $headerPadded;

            return $headerPadded;
        };

        /**
         * Get count of rows of header and all options. Used for erasing menu.
         *
         * @param  String $header
         * @param  Array<String> $options
         * @return Int
         */
        $getRowCount = function(String $header, Array $options) use($getPaddedHeader){
            if($this->rowCount) {
                return $this->rowCount;
            }
            $rowCount = array_sum(array_map(function($t) {
                $ttext = new MacrameText($t);
                return count(explode(PHP_EOL, $ttext->wrap()->get()));
            }, $options)) + $getPaddedHeader($header)->rowCount();
            $this->rowCount = $rowCount;
            return $rowCount;
        };

        /**
         * Get array of options with padding and styling.
         *
         * @param  Array $options
         * @param  Int $selected
         * @return Array
         */
        $getOptionTexts = function(Array $options, Int $selected)  use($getRightPad, $getLeftPad, $maxWidth){
            if(isset($this->options[$selected])) {
                return $this->options[$selected];
            }

            // get array of options suitable for display, ie. with multi-line handled and padding added
            $displayOptions = array_map(function($t) use($getRightPad, $getLeftPad, $maxWidth){
                $ttext = new MacrameText($t);
                $optionWrapped = explode(PHP_EOL, $ttext->wrap()->get());
                return join(PHP_EOL, array_map(fn($t) => $getLeftPad($t, $maxWidth, 1).$t.$getRightPad($t, $maxWidth),$optionWrapped));
            }, $options);

            // write each menu option with padding for alignement and current selected option highlighted
            $optionTexts = [];
            foreach($displayOptions as $i => $t) {
                // handle the selected option styling
                if($i == $selected) {
                    $text = new MacrameText($t);

                    if(isset($this->colourSelected)) {
                        $text->colour($this->colourSelected);
                    }

                    foreach($this->styleSelected as $style) {
                        $text->style($style);
                    }

                    // if no styles are colours are applied to selected, use reverse
                    if(count($this->styleSelected) == 0 && !isset($this->colourSelected)) {
                        $text->reverse();
                    }
                }
                // handle non-selected option styling
                else {
                    $text = new MacrameText($t);

                    if(isset($this->colourOption)) {
                        $text->colour($this->colourOption);
                    }

                    foreach($this->styleOption as $style) {
                        $text->style($style);
                    }
                }

                // align the menu option
                switch($this->menuAlignment) {
                    case LEFT:
                        $text->left();
                        break;
                    case RIGHT:
                        $text->right();
                        break;
                    case CENTRE:
                        $text->centre();
                        break;
                }

                $optionTexts[] = $text;
            }

            $this->options[$selected] = $optionTexts;
            return $optionTexts;
        };

        $rowCount = $getRowCount($header, $options);
        $header = $getPaddedHeader($header);
        $optionTexts = $getOptionTexts($options, $selected);

        if(!$initial) {
            IO::eraseLines($rowCount);
        }

        $header->write(true);
        array_map(fn($o) => $o->write(true), $optionTexts);
    }
}
