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
     * @var Array
     * @access private
     */
    private Array $styleOption = [];

    /**
     * Styles for selected
     * @var Array
     * @access private
     */
    private Array $styleSelected = [];

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
     * Execute an interactive menu and return the selected option
     *
     * @param  Array<String> $options The array of options in the menu
     * @param  ?String       $header  The optional header to show
     * @return String  The selected option string
     */
    public function interactive(Array $options, ?String $header = null):String
    {
        // strip all macrame formatting tags from the string
        $optionsTagless = array_map(fn($t) => preg_replace('/<![A-Za-z]+!>/', '', $t), $options);

        // initial selected element is the first
        $selectedIndex = 0;

        // output menu
        $this->printInteractiveMenu($optionsTagless, $selectedIndex, $header);

        // poll for user input
        while(true) {
            $key = IO::keyStroke();

            // handle user input
            switch(ord($key)) {

                // down menu
                case KEY_DOWN_ARROW:
                case KEY_TAB:
                    $selectedIndex = $selectedIndex >= count($optionsTagless) - 1 ? 0 : $selectedIndex + 1; // rollover to top
                    $this->printInteractiveMenu($optionsTagless, $selectedIndex, $header);
                    break;

                // up menu
                case KEY_UP_ARROW:
                    $selectedIndex = $selectedIndex <= 0 ? count($optionsTagless) - 1 : $selectedIndex - 1; // rollover to bottom
                    $this->printInteractiveMenu($optionsTagless, $selectedIndex, $header);
                    break;

                // select item
                case KEY_RETURN:
                    return $options[$selectedIndex];
            }
        }
    }

    /**
     * Prints an interactive menu
     *
     * @param  Array<String>  $options
     * @param  Int $selected The index of the option to show as currentlys selected
     * @param  ?String $header The optional header
     * @return void
     */
    private function printInteractiveMenu(Array $options, Int $selected, ?String $header = null):void
    {
        $headerText = new MacrameText($header);

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
        $getPad = function(String $text, Int $width):String {
            $padAmount = (int)floor(($width - $this->text->mb_strwidth_ansi($text)));
            return join(array_fill(0, $padAmount, " "));
        };

        $maxWidth = $getMaxWidth($options);

        // get array of options suitable for display, ie. with multi-line handled and padding added
        $displayOptions = array_map(function($t) use($getPad, $maxWidth){
            $ttext = new MacrameText($t);
            $optionWrapped = explode(PHP_EOL, $ttext->wrap()->get());
            return join(PHP_EOL, array_map(fn($t) => ' '.$t.$getPad($t, $maxWidth),$optionWrapped));
        }, $options);

        // determine how many rows the menu is so we can erase them before redraw
        $rowCount = array_sum(array_map(function($t) {
            $ttext = new MacrameText($t);
            return count(explode(PHP_EOL, $ttext->wrap()->get()));
        }, $options)) + $headerText->rowCount();

        // erase the menu
        IO::eraseLines($rowCount);

        // write the menu header
        $headerText->write(true);

        // write each menu option with current selected highlited reverse
        foreach($displayOptions as $i => $t) {
            if($i == $selected) {
                $text = new MacrameText($t);

                if(isset($this->colourSelected)) {
                    $text->colour($this->colourSelected);
                }

                foreach($this->styleSelected as $style) {
                    $text->style($style);
                }

                $text->reverse();
            }
            else {
                $text = new MacrameText($t);

                if(isset($this->colourOption)) {
                    $text->colour($this->colourOption);
                }

                foreach($this->styleOption as $style) {
                    $text->style($style);
                }
            }
            $text->write(true);
        }
    }
}
