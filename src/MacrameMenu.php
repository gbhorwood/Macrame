<?php

namespace Gbhorwood\Macrame;

use Gbhorwood\Macrame\MacrameIO as IO;

/**
 * Key codes
 */
if (!defined('KEY_RETURN')) {
    define('KEY_RETURN', 10);
}
if (!defined('KEY_UP_ARROW')) {
    define('KEY_UP_ARROW', 65);
}
if (!defined('KEY_DOWN_ARROW')) {
    define('KEY_DOWN_ARROW', 66);
}
if (!defined('KEY_RIGHT_ARROW')) {
    define('KEY_RIGHT_ARROW', 67);
}
if (!defined('KEY_LEFT_ARROW')) {
    define('KEY_LEFT_ARROW', 68);
}
if (!defined('KEY_TAB')) {
    define('KEY_TAB', 9);
}
if (!defined('KEY_BACKSPACE')) {
    define('KEY_BACKSPACE', 127);
}
if (!defined('KEY_DELETE')) {
    define('KEY_DELETE', 126);
}
if (!defined('KEY_O')) {
    define('KEY_O', 111);
}

/**
 * Alignment definitions
 */
if (!defined('LEFT')) {
    define('LEFT', 0);
}
if (!defined('CENTRE')) {
    define('CENTRE', 2);
}
if (!defined('RIGHT')) {
    define('RIGHT', 1);
}

/**
 * Handle menus
 *
 */
class MacrameMenu
{
    /**
     * Foreground colour of option
     * @var ?String
     * @access private
     */
    private ?String $colourOption = null;

    /**
     * Foreground colour of selected option
     * @var ?String
     * @access private
     */
    private ?String $colourSelected = null;

    /**
     * Styles for options
     * @var Array<String>
     * @access private
     */
    private array $styleOption = [];

    /**
     * Styles for selected
     * @var Array<String>
     * @access private
     */
    private array $styleSelected = [];

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
     * @var Array<Array<String>>
     * @access private
     */
    private array $interactiveMenuDisplays = [];

    /**
     * Memoization
     * @var Array<Array<String>>
     * @access private
     */
    private array $horizontalMenuDisplays = [];

    /**
     * Memoization
     * @var Array<String>
     * @access private
     */
    private array $dateMenuDisplays = [];

    /**
     * Constructor
     *
     */
    public function __construct()
    {
    }

    /**
     * Set alignment of options to left
     *
     * @return MacrameMenu
     */
    public function optionLeft(): MacrameMenu
    {
        $this->optionAlignment = LEFT;
        return $this;
    }

    /**
     * Set alignment of options to right
     *
     * @return MacrameMenu
     */
    public function optionRight(): MacrameMenu
    {
        $this->optionAlignment = RIGHT;
        return $this;
    }

    /**
     * Set alignment of options to centre
     *
     * @return MacrameMenu
     */
    public function optionCentre(): MacrameMenu
    {
        $this->optionAlignment = CENTRE;
        return $this;
    }

    /**
     * Alias of optionCentre()
     *
     * @return MacrameMenu
     */
    public function optionCenter(): MacrameMenu
    {
        return $this->optionCentre();
    }

    /**
     * Set alignment of menu to left
     *
     * @return MacrameMenu
     */
    public function menuLeft(): MacrameMenu
    {
        $this->menuAlignment = LEFT;
        return $this;
    }

    /**
     * Set alignment of menu to right
     *
     * @return MacrameMenu
     */
    public function menuRight(): MacrameMenu
    {
        $this->menuAlignment = RIGHT;
        return $this;
    }

    /**
     * Set alignment of menu to centre
     *
     * @return MacrameMenu
     */
    public function menuCentre(): MacrameMenu
    {
        $this->menuAlignment = CENTRE;
        return $this;
    }

    /**
     * Alias of menuCentre()
     *
     * @return MacrameMenu
     */
    public function menuCenter(): MacrameMenu
    {
        return $this->menuCentre();
    }

    /**
     * Set foreground colour of options of menu
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colourOption(String $colour): MacrameMenu
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
    public function colorOption(String $colour): MacrameMenu
    {
        return $this->colourOption($colour);
    }

    /**
     * Set foreground colour of the selected option of menu
     *
     * @param  String $colour The colour as defined in MacrameText
     * @return MacrameMenu
     */
    public function colourSelected(String $colour): MacrameMenu
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
    public function colorSelected(String $colour): MacrameMenu
    {
        return $this->colourSelected($colour);
    }

    /**
     * Set style of options of menu
     *
     * @param  String $style The style as defined in MacrameText
     * @return MacrameMenu
     */
    public function styleOption(String $style): MacrameMenu
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
    public function styleSelected(String $style): MacrameMenu
    {
        $this->styleSelected[] = $style;
        return $this;
    }

    /**
     * Set whether to erase menu output after menu completed. Toggle
     *
     * @return MacrameMenu
     */
    public function erase(): MacrameMenu
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
    public function interactive(array $options, ?String $header = null): String
    {
        IO::hideCursor();

        // initial selected element is the first
        $selectedIndex = 0;

        // strip all macrame formatting and ansi tags from all options
        $optionsSanitized = MenuBuilder::stripOptionsFormatting($options);

        // build the array of displayable menus, one per each selection state
        $this->interactiveMenuDisplays = InteractiveMenu::get($header, $options, $this->optionAlignment, $this->menuAlignment, $this->colourOption, $this->styleOption, $this->colourSelected, $this->styleSelected);

        // print the initial menu before user input
        $this->printInteractiveMenu($selectedIndex, true);

        // poll for user input
        while (true) {
            $key = IO::keyStroke();

            // handle user input
            switch (ord($key)) {

                // down menu
                case KEY_DOWN_ARROW:
                case KEY_TAB:
                    $selectedIndex = $selectedIndex >= count($optionsSanitized) - 1 ? 0 : $selectedIndex + 1; // rollover to top
                    $this->printInteractiveMenu($selectedIndex);
                    break;

                    // up menu
                case KEY_UP_ARROW:
                    $selectedIndex = $selectedIndex <= 0 ? count($optionsSanitized) - 1 : $selectedIndex - 1; // rollover to bottom
                    $this->printInteractiveMenu($selectedIndex);
                    break;

                    // select item
                case KEY_RETURN:
                    IO::showCursor();
                    if ($this->erase) {
                        IO::eraseLines(count($this->interactiveMenuDisplays[$selectedIndex]) + 1);
                    }
                    return $optionsSanitized[$selectedIndex];

                    // all other keys leader functionality
                default:
                    for ($i = 0; $i < count($optionsSanitized);$i++) {
                        if (str_starts_with(strtolower($optionsSanitized[$i]), strtolower($key))) {
                            $selectedIndex = $i;
                            break;
                        }
                    }
                    $this->printInteractiveMenu($selectedIndex);
                    break;
            }
        }
    }

    /**
     * Execute a horizontal menu and return the selected option
     *
     * @param  Array<String> $options The array of options in the menu
     * @param  ?String       $header  The optional header to show
     * @return String  The selected option string
     */
    public function horizontal(array $options, ?String $header = null): String
    {
        IO::hideCursor();

        // initial selected element is the first
        $selectedIndex = 0;

        // strip all macrame formatting and ansi tags from all options
        $optionsSanitized = MenuBuilder::stripOptionsFormatting($options);

        // build the array of displayable menus, one per each selection state
        $this->horizontalMenuDisplays = HorizontalMenu::get($header, $options, $this->optionAlignment, $this->menuAlignment, $this->colourOption, $this->styleOption, $this->colourSelected, $this->styleSelected);

        // print the initial menu before user input
        $this->printHorizontalMenu($selectedIndex, true);

        // poll for user input
        while (true) {
            $key = IO::keyStroke();

            // handle user input
            switch (ord($key)) {

                // next menu item
                case KEY_DOWN_ARROW:
                case KEY_RIGHT_ARROW:
                case KEY_TAB:
                    $selectedIndex = $selectedIndex >= count($optionsSanitized) - 1 ? 0 : $selectedIndex + 1; // rollover to top
                    $this->printHorizontalMenu($selectedIndex);
                    break;

                    // previous menu item
                case KEY_UP_ARROW:
                case KEY_LEFT_ARROW:
                    $selectedIndex = $selectedIndex <= 0 ? count($optionsSanitized) - 1 : $selectedIndex - 1; // rollover to bottom
                    $this->printHorizontalMenu($selectedIndex);
                    break;

                    // select item
                case KEY_RETURN:
                    IO::showCursor();
                    if ($this->erase) {
                        IO::eraseLines(count($this->horizontalMenuDisplays[$selectedIndex]));
                    }
                    return $options[$selectedIndex];

                    // all other keys
                default:
                    for ($i = 0; $i < count($optionsSanitized);$i++) {
                        if (str_starts_with(strtolower($optionsSanitized[$i]), strtolower($key))) {
                            $selectedIndex = $i;
                            break;
                        }
                    }
                    $this->printHorizontalMenu($selectedIndex);
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
    public function datePicker(String $date, ?String $header = null): String
    {
        IO::hideCursor();

        $index = 0;
        $leaderString = '';

        /**
         * Validate date
         */
        try {
            $dateObj = new \DateTime($date);
        } catch (\Exception $e) {
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
        $display = function (\DateTime $dateObj, Int $index, $initial = false) use ($header) {
            $parts[0] = $dateObj->format('Y');
            $parts[1] = $dateObj->format('M');
            $parts[2] = $dateObj->format('d');
            $this->dateMenuDisplays = HorizontalMenu::buildOne($header, $parts, $index, $this->optionAlignment, $this->menuAlignment, $this->colourOption, $this->styleOption, $this->colourSelected, $this->styleSelected);

            if (!$initial) {
                IO::eraseLines(count($this->dateMenuDisplays));
            }
            array_map(fn ($l) => IO::writeStdout($l.PHP_EOL), $this->dateMenuDisplays);
        };

        /**
         * Function to increment/decrement the date object by one unit. ie. on arrow up or down.
         *
         * @param  DateTime $dateObj   The DateTime object
         * @param  Int      $index     The field in the menu that maps to the date part. ie. 0 for year, 1 for month and 2 for day.
         * @param  String   $increment The amount of increment/decrement for DateTime's modify(), ie. '+1'
         * @return DateTime
         */
        $update = function (\DateTime $dateObj, Int $index, String $increment): \DateTime {
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
        $handleLeaderKeys = function (\DateTime $dateObj, Int $index, String $leaderString) {
            $parts[0] = $dateObj->format('Y');
            $parts[1] = $dateObj->format('m');
            $parts[2] = $dateObj->format('d');

            switch ($index) {
                /**
                 * Year
                 */
                case 0:
                    $leaderString = strlen($leaderString) > 4 ? substr($leaderString, -1) : $leaderString;
                    $y = str_pad($leaderString, 4, '0');
                    return [
                        new \DateTime($y.$parts[1].$parts[2]),
                        $leaderString,
                    ];

                    /**
                     * Month
                     */
                case 1:
                    $leaderString = strlen($leaderString) > 3 ? substr($leaderString, -1) : $leaderString;
                    $monthNumber = $parts[1];
                    $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                    $valid = false;
                    foreach ($months as $monthIndex => $month) {
                        if (preg_match("/^$leaderString/", $month)) {
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
                case 2:
                    $leaderString = strlen($leaderString) > 2 ? substr($leaderString, -1) : $leaderString;
                    $d = substr(str_pad($leaderString, 2, '0', STR_PAD_LEFT), -2);
                    return [
                        new \DateTime($parts[0].$parts[1].$d),
                        $leaderString,
                    ];
            }
        };

        $display($dateObj, $index, true);

        // poll for user input
        while (true) {
            $key = IO::keyStroke();

            // handle user input
            switch (ord($key)) {

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
                    if ($this->erase) {
                        IO::eraseLines(count($this->dateMenuDisplays));
                    }
                    return (string)$dateObj->format('Y-m-d');
            }
        }
    }

    /**
     * Print out the displayable interactive menu for the selected option. If $initial is true,
     * no erasing is done.
     *
     * @param  Int $selected
     * @param  Bool $initial
     * @return void
     */
    private function printInteractiveMenu(Int $selected, Bool $initial = false)
    {
        // get count of all lines of all menu elements and erase that many lines if not initial menu display
        if (!$initial) {
            IO::eraseLines(array_sum(array_map(fn ($l) => count(explode(PHP_EOL, $l)), $this->interactiveMenuDisplays[$selected])));
        }
        array_map(fn ($l) => IO::writeStdout($l.PHP_EOL), $this->interactiveMenuDisplays[$selected]);
    }


    /**
     * Print out the displayable horizontal menu for the selected option. If $initial is true,
     * no erasing is done.
     *
     * @param  Int $selected
     * @param  Bool $initial
     * @return void
     */
    private function printHorizontalMenu(Int $selected, Bool $initial = false)
    {
        if (!$initial) {
            IO::eraseLines(count($this->horizontalMenuDisplays[$selected]));
        }
        array_map(fn ($l) => IO::writeStdout($l.PHP_EOL), $this->horizontalMenuDisplays[$selected]);
    }
}



class MenuBuilder
{
    /**
     * Removes all ansi formatting and macrame formatting tags from
     * all options strings in the options array.
     *
     * @param  Array<String> $options
     * @return Array<String>
     */
    public static function stripOptionsFormatting(array $options): array
    {
        return array_map(fn ($t) => MacrameText::stripFormatting($t), $options);
    }

    /**
     * Gets the widht of the longest line of all lines in header and options, for inner alignment.
     *
     * @param  String $header
     * @param  Array<String>  $options
     * @return Int
     */
    public static function maxWidth(String $header, array $options): Int
    {
        return max([self::maxOptionWidth($options), self::maxHeaderWidth($header)]);
    }

    /**
     * Get the width of the longest line of text in the array of options
     *
     * @param  Array<String> $options
     * @return Int
     */
    private static function maxOptionWidth(array $options): Int
    {
        // all options as array of lines
        $optionsByLines = array_map(function ($t) {
            $ttext = new MacrameText($t);
            return explode(PHP_EOL, $ttext->wrap()->get());
        }, $options);

        // flatten array of option line array
        $optionsFlattened = [];
        array_walk_recursive(
            $optionsByLines,
            function ($item, $key) use (&$optionsFlattened) {
                $optionsFlattened[] = $item;
            }
        );

        // return ansi-safe mb_strwidth of longest line
        $text = new MacrameText();
        return max(array_map(fn ($t) => $text->mb_strwidth_ansi($t), $optionsFlattened));
    }

    /**
     * Get the width of the longest line of text in the header string
     *
     * @param  String $header
     * @return Int
     */
    public static function maxHeaderWidth(String $header): Int
    {
        $text = new MacrameText($header);
        $headerLines = explode(PHP_EOL, $text->wrap()->get());
        return max(array_map(fn ($t) => $text->mb_strwidth_ansi($t), $headerLines));
    }

}


/**
 * Build displays of interactive menus
 *
 */
class InteractiveMenu
{
    /**
     * Build and return array of menus for all select cases. Each menu is an array of of lines, for use with printInteractiveMenu().
     *
     * @param  String $header
     * @param  Array<String> $options
     * @param  Int $optionsAlignment
     * @param  Int $menuAlignment
     * @param  String $colourOption
     * @param  Array<String> $styleOption
     * @param  String $colourSelected
     * @param  Array<String> $styleSelected
     * @return Array<Array<String>>
     */
    public static function get(String $header, array $options, Int $optionsAlignment = CENTRE, Int $menuAlignment = CENTRE, String $colourOption = null, array $styleOption = [], String $colourSelected = null, array $styleSelected = []): array
    {
        // remove all ansi formatting from option strings
        $optionsSanitized = MenuBuilder::stripOptionsFormatting($options);

        $maxWidth = MenuBuilder::maxWidth($header, $options);

        // align all lines of all options in the menu box
        $optionsAligned = self::innerAlignOptions($optionsSanitized, $maxWidth, $menuAlignment, $optionsAlignment);

        // get left padding spaces for menu alignmnet
        $lpad = self::leftPad($maxWidth, $menuAlignment);

        // align all lines of header in menu box. headers is array of lines.
        $headerAligned = self::headerAligned($header, $maxWidth, $optionsAlignment);

        // pad header lines for screen alignment
        $headerAligned = array_map(fn ($h) => $lpad.$h, $headerAligned);

        $displayableMenus = [];
        for ($i = 0; $i < count($optionsAligned); $i++) {
            $displayableMenus[$i] = $headerAligned;
            for ($j = 0; $j < count($optionsAligned); $j++) {

                // apply selected styling
                if ($i == $j) {
                    if ($colourSelected || $styleSelected) {
                        $styled = join(PHP_EOL, array_map(fn ($k) => $lpad.self::style($k, $colourSelected, $styleSelected), explode(PHP_EOL, $optionsAligned[$j])));
                    } else {
                        $styled = join(PHP_EOL, array_map(fn ($k) => $lpad.self::reverse($k), explode(PHP_EOL, $optionsAligned[$j])));
                    }
                }
                // apply regular styling
                else {
                    $styled = join(PHP_EOL, array_map(fn ($k) => $lpad.self::style($k, $colourOption, $styleOption), explode(PHP_EOL, $optionsAligned[$j])));
                }

                $displayableMenus[$i][] = $styled;
            }
        }

        return $displayableMenus;
    }

    /**
     * Apply the colour and style(s), if any, to a $line.
     *
     * @param  String $line
     * @param  String $colour
     * @param  Array<String>  $styles
     */
    public static function style(String $line, String $colour = null, array $styles = []): String
    {
        $text = new MacrameText($line);

        if ($colour) {
            $text->colour($colour);
        }

        if (count($styles) > 0) {
            array_map(fn ($s) => $text->style($s), $styles);
        }

        return $text->get();
    }

    public static function reverse(String $line): String
    {
        $text = new MacrameText($line);
        return $text->reverse()->get();
    }

    /**
     * Align all lines of all elements of $options in the menu box to $optionsAlignment.
     * $menuAlignment is for determining if indent is applied on options.
     *
     * @param  Array<String> $options
     * @param  Int $maxWidth
     * @param  Int $menuAlignment
     * @param  Int $optionsAlignment
     * @return Array<String>
     */
    public static function innerAlignOptions(array $options, Int $maxWidth, Int $menuAlignment, Int $optionsAlignment = LEFT): array
    {
        // only indent options list on left-aligned menu that is left-aligned in the terminal
        $indent = 0;
        if ($menuAlignment == LEFT && $optionsAlignment == LEFT) {
            $indent = 1;
        }

        return array_map(function ($o) use ($maxWidth, $indent, $optionsAlignment) {
            return join(PHP_EOL, array_map(fn ($l) => self::align($l, $maxWidth, $optionsAlignment, $indent), explode(PHP_EOL, $o)));
        }, $options);
    }

    /**
     * Align all lines in the header inside the menu box
     *
     * @param  String $header The header
     * @param  Int    $maxWidth
     * @param  Int    $optionsAlignment One of the constants LEFT, RIGHT, CENTRE
     * @return Array<String>
     * @see align
     */
    public static function headerAligned(String $header, Int $maxWidth, Int $optionsAlignment = LEFT): array
    {
        $text = new MacrameText($header);
        $header = $text->wrap()->get();

        // header is never indented
        return array_map(fn ($h) => self::align($h, $maxWidth, $optionsAlignment, 0), explode(PHP_EOL, $header));
    }

    /**
     * Get a line of text aligned within the menu box to the alignment of $innerAlignment
     *
     * @param  String $line The line to align in the menu box
     * @param  Int    $width The width of the menu box
     * @param  Int    $innerAlignment One of the constants LEFT, RIGHT, CENTRE
     * @param  Int    $indent Number of spaces to left indent. Default 1.
     * @return String
     */
    private static function align(String $line, Int $width, Int $innerAlignment, Int $indent = 1): String
    {
        $text = new MacrameText();
        $textWidth = $text->mb_strwidth_ansi($line);
        $width += $indent;

        $l = 0;
        $r = 0;

        if ($innerAlignment == CENTRE) {
            $width += 1;
            $l = floor(($width - $textWidth) / $innerAlignment);
            $r = ($width - $textWidth) - $l;
        }
        if ($innerAlignment == LEFT) {
            $l = $indent;
            $r = ($width - $textWidth) - $l;
        }
        if ($innerAlignment == RIGHT) {
            $l = (int)($width - $textWidth);
            $r = 1;
        }

        return join(array_fill(0, $l, ' ')).$line.join(array_fill(0, $r, ' '));
    }

    /**
     * Get string of spaces to left pad for menu alignment
     *
     * @param  Int $maxWidth The width of the widest line in options and header
     * @param  Int $menuAlignment The alignment of the menu. One of LEFT, CENTRE, RIGHT
     * @return String
     */
    private static function leftPad(Int $maxWidth, Int $menuAlignment): String
    {
        $padamt = $menuAlignment ? (IO::getColWidth() - $maxWidth) / $menuAlignment : 0;
        return $padamt ? join(array_fill(0, $padamt, ' ')) : '';
    }
}

/**
 * Build displays of horizontal menus
 *
 */
class HorizontalMenu
{
    /**
     * Build and return array of menus for all select cases. Each menu is an array of of lines, for use with printHorizontalMenu().
     *
     * @param  String $header
     * @param  Array<String> $options
     * @param  Int $optionsAlignment
     * @param  Int $menuAlignment
     * @param  String $colourOption
     * @param  Array<String> $styleOption
     * @param  String $colourSelected
     * @param  Array<String> $styleSelected
     * @return Array<Array<String>>
     */
    public static function get(String $header, array $options, Int $optionsAlignment = CENTRE, Int $menuAlignment = CENTRE, String $colourOption = null, array $styleOption = [], String $colourSelected = null, array $styleSelected = [])
    {
        // remove all ansi formatting from option strings
        $optionsSanitized = MenuBuilder::stripOptionsFormatting($options);

        $maxWidth = self::maxWidth($header, $options);

        // get left padding spaces for menu alignmnet
        $lpad = self::leftPad($maxWidth, $menuAlignment);

        // align all lines of header in menu box. headers is array of lines.
        $headerAligned = InteractiveMenu::headerAligned($header, $maxWidth, $optionsAlignment);

        // pad header lines for screen alignment
        $headerAligned = array_map(fn ($h) => $lpad.$h, $headerAligned);

        // build array of displayable menu arrays, each one representing the display of a selected option
        $displayableMenus = [];
        for ($i = 0; $i < count($options); $i++) {
            $displayableMenus[$i] = $headerAligned;

            $currentOptions = [];
            for ($j = 0; $j < count($options); $j++) {
                $currentOptions[$j] = $options[$j];

                // apply styles
                if ($i == $j) {
                    if ($colourSelected || $styleSelected) {
                        $currentOptions[$j] = InteractiveMenu::style($options[$j], $colourSelected, $styleSelected);
                    } else {
                        $currentOptions[$j] = InteractiveMenu::reverse($options[$j]);
                    }
                } else {
                    $currentOptions[$j] = InteractiveMenu::style($options[$j], $colourOption, $styleOption);
                }
            }
            $displayableMenus[$i][] = $lpad.self::joinOptions($currentOptions);
        }

        return $displayableMenus;
    }

    /**
     * Get one displayable menu as an array of lines for the selected option $selected.
     *
     * @param  String $header
     * @param  Array<String> $options
     * @param  Int $selected The id of the option to display as selected
     * @param  Int $optionsAlignment
     * @param  Int $menuAlignment
     * @param  String $colourOption
     * @param  Array<String> $styleOption
     * @param  String $colourSelected
     * @param  Array<String> $styleSelected
     * @return Array<String>
     */
    public static function buildOne(String $header, array $options, Int $selected, Int $optionsAlignment = CENTRE, Int $menuAlignment = CENTRE, String $colourOption = null, array $styleOption = [], String $colourSelected = null, array $styleSelected = [])
    {
        // remove all ansi formatting from option strings
        $optionsSanitized = MenuBuilder::stripOptionsFormatting($options);

        $maxWidth = self::maxWidth($header, $options);

        // get left padding spaces for menu alignmnet
        $lpad = self::leftPad($maxWidth, $menuAlignment);

        // align all lines of header in menu box. headers is array of lines.
        $headerAligned = InteractiveMenu::headerAligned($header, $maxWidth, $optionsAlignment);

        // pad header lines for screen alignment
        $headerAligned = array_map(fn ($h) => $lpad.$h, $headerAligned);

        // add aligned header to top of menu array
        $menu = $headerAligned;

        // build array of styled options
        $currentOptions = [];
        for ($i = 0; $i < count($options); $i++) {
            if ($i == $selected) {
                if ($colourSelected || $styleSelected) {
                    $currentOptions[$i] = InteractiveMenu::style($options[$i], $colourSelected, $styleSelected);
                } else {
                    $currentOptions[$i] = InteractiveMenu::reverse($options[$i]);
                }
            } else {
                $currentOptions[$i] = InteractiveMenu::style($options[$i], $colourOption, $styleOption);
            }
        }

        // add styled horizontal display options to menu array
        $menu[] = $lpad.self::joinOptions($currentOptions);

        return $menu;
    }

    /**
     * Get the largest width of all lines in $header and $options
     *
     * @param  String $header
     * @param  Array<String> $options
     * @return Int
     */
    public static function maxWidth(String $header, array $options): Int
    {
        $text = new MacrameText($header);
        return max([$text->mb_strwidth_ansi(self::joinOptions($options)), MenuBuilder::maxHeaderWidth($header)]);
    }

    /**
     * Get all $options as a single line for a horizontal menu
     *
     * @param  Array<String> $options
     * @return String
     */
    public static function joinOptions(array $options)
    {
        return join('  ', $options);
    }

    /**
     * Get string of spaces to left pad for menu alignment
     *
     * @param  Int $maxWidth The width of the widest line in options and header
     * @param  Int $menuAlignment The alignment of the menu. One of LEFT, CENTRE, RIGHT
     * @return String
     */
    private static function leftPad(Int $maxWidth, Int $menuAlignment): String
    {
        $padamt = $menuAlignment ? (IO::getColWidth() - $maxWidth) / $menuAlignment : 0;
        return $padamt ? join(array_fill(0, $padamt, ' ')) : '';
    }
}
