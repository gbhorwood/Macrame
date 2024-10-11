<?php
namespace Gbhorwood\Macrame;

use \Gbhorwood\Macrame\MacrameIO as IO;
use \Gbhorwood\Macrame\MacrameText as Text;

/**
 * Spinner rotation rates
 */
define('DELAY_SLOW', 7500000);
define('DELAY_MED', 300000);
define('DELAY_FAST', 150000);
define('DELAY_VERY_FAST', 50000);

/**
 * ANSI: Convenience defines
 */
if(!defined('BACKSPACE')) define('BACKSPACE', chr(8));

/**
 * Handle creation and display of animated text spinners
 *
 */
class MacrameSpinner {

    /**
     * Array of animation character arrays keyed by animation name
     * @var Array
     * @access private
     */
    private Array $animations = [
        'standard' => ['|', '/', '-', '\\', '|', '/', '-'],
		"dots 1" => [ "⠄", "⠆", "⠇", "⠋", "⠙", "⠸", "⠰", "⠠", "⠰", "⠸", "⠙", "⠋", "⠇", "⠆" ],
		"dots 2" => [ "⠁", "⠉", "⠙", "⠚", "⠒", "⠂", "⠂", "⠒", "⠲", "⠴", "⠤", "⠄", "⠄", "⠤", "⠴", "⠲", "⠒", "⠂", "⠂", "⠒", "⠚", "⠙", "⠉", "⠁" ],
		"dots 3" => [ "⠋", "⠙", "⠚", "⠒", "⠂", "⠂", "⠒", "⠲", "⠴", "⠦", "⠖", "⠒", "⠐", "⠐", "⠒", "⠓", "⠋" ],
		"dots 4" => [ "⠈", "⠉", "⠋", "⠓", "⠒", "⠐", "⠐", "⠒", "⠖", "⠦", "⠤", "⠠", "⠠", "⠤", "⠦", "⠖", "⠒", "⠐", "⠐", "⠒", "⠓", "⠋", "⠉", "⠈" ],
		"dots 5" => [ "⠁", "⠁", "⠉", "⠙", "⠚", "⠒", "⠂", "⠂", "⠒", "⠲", "⠴", "⠤", "⠄", "⠄", "⠤", "⠠", "⠠", "⠤", "⠦", "⠖", "⠒", "⠐", "⠐", "⠒", "⠓", "⠋", "⠉", "⠈", "⠈" ],
		"cycle 1" => [ "⠁", "⠂", "⠄", "⡀", "⢀", "⠠", "⠐", "⠈" ],
		"cycle 2" => [ "⣼", "⣹", "⢻", "⠿", "⡟", "⣏", "⣧", "⣶" ],
		"cycle 3" => [ "⢄", "⢂", "⢁", "⡁", "⡈", "⡐", "⡠" ],
		"cycle 4" => [ "⢹", "⢺", "⢼", "⣸", "⣇", "⡧", "⡗", "⡏" ],
		"cycle 5" => [ "⣾", "⣽", "⣻", "⢿", "⡿", "⣟", "⣯", "⣷" ],
        "cycle 6" => [ "⠋", "⠙", "⠹", "⠸", "⠼", "⠴", "⠦", "⠧", "⠇", "⠏" ],
		"star" => [ "✶", "✸", "✹", "✺", "✹", "✷" ],
		"grow" => [ "▁", "▃", "▄", "▅", "▆", "▇", "▆", "▅", "▄", "▃" ],
		"stretch" => [ "▏", "▎", "▍", "▌", "▋", "▊", "▉", "▊", "▋", "▌", "▍", "▎" ],
		"corners 1" => [ "▌", "▀", "▐", "▄" ],
		"corners 2" => [ "◢", "◣", "◤", "◥" ],
		"pipe" => [ "┤", "┘", "┴", "└", "├", "┌", "┬", "┐" ],
		"balloon" => [ " ", ".", "o", "O", "@", "*", " " ],
		"bounce 1" => [ "⠁", "⠂", "⠄", "⠂" ],
		"bounce 2" => [ ".", "o", "O", "°", "O", "o", "." ],
		"bounce 3" => [ "☱", "☲", "☴" ],
		"rolling square" => [ "◰", "◳", "◲", "◱" ],
		"rolling circle 1" => [ "◴", "◷", "◶", "◵" ],
		"rolling circle 2" => [ "◐", "◓", "◑", "◒" ],
		"pulse 1" => [ "⊶", "⊷" ],
		"pulse 2" => [ "▫", "▪" ],
		"pulse 3" => [ "□", "■" ],
		"pulse 4" => [ "▮", "▯" ],
		"pulse 5" => [ "◍", "◌" ],
		"pulse 6" => [ "◉", "◎" ],
		"pulse 7" => [ "⧇", "⧆" ],
		"pulse 8" => [ "☗", "☖" ],
		"pulse 9" => [ "ဝ", "၀" ],
		"pulse 10" => [ "◡", "⊙", "◠" ],
		"pulse 11" => [ "▓", "▒", "░" ],
		"arrow" => [ "←", "↖", "↑", "↗", "→", "↘", "↓", "↙" ],
		"arrow emoji" => [ "⬆️ ", "↗️ ", "➡️ ", "↘️ ", "⬇️ ", "↙️ ", "⬅️ ", "↖️ " ],
		"heart emoji" => [ "💛 ", "💙 ", "💜 ", "💚 ", "❤️ " ],
		"clock emoji" => [ "🕛 ", "🕐 ", "🕑 ", "🕒 ", "🕓 ", "🕔 ", "🕕 ", "🕖 ", "🕗 ", "🕘 ", "🕙 ", "🕚 " ],
		"earth emoji" => [ "🌍 ", "🌎 ", "🌏 " ],
		"moon emoji" => [ "🌑 ", "🌒 ", "🌓 ", "🌔 ", "🌕 ", "🌖 ", "🌗 ", "🌘 " ],
    ];

    /**
     * Integers used for usleep() to adjust animation speed
     * @var Array
     * @access private
     */
    private Array $speeds = [
        'slow' => 300000,
        'medium' => 150000,
        'fast' => 50000,
        'very fast' => 10000,
    ];

    /**
     * Array containting animation chars for selected animation
     * @var Array
     * @access private
     */
    private Array $animation = [];

    /**
     * The usleep() value for animation speed
     * @var Int
     * @access private
     */
    private Int $speed = 300000;

    /**
     * Text prompt to preceed animation
     * @var ?String
     * @access private
     */
    private ?String $prompt = null;

    /**
     * MacrameText to contain prompt and animation character
     * @var ?MacrameText
     * @access private
     */
    private ?MacrameText $output = null;

    /**
     * Constructor
     *
     * @param  ?String $animation Name of the animation. Default 'standard'
     * @return void
     */
    public function __construct(?String $animation=null) 
    {
        $this->animation = $this->animations['standard'];
        if(isset($this->animations[$animation])) {
            $this->animation = $this->animations[$animation];
        }

        $this->output = new MacrameText();
    }

    /**
     * Set the animation speed to a value of 'slow', 'medium', 'fast', 'very fast'
     *
     * @param  String $speed Text identifying speed; must be a key of $speeds[], ie. 'fast'
     * @return MacrameSpinner
     */
    public function speed(String $speed):MacrameSpinner
    {
        if(isset($this->speeds[$speed])) {
            $this->speed = $this->speeds[$speed];
        }
        return $this;
    }

    /**
     * Set the text prompt to preceed the spinner animation
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function prompt(String $prompt):MacrameSpinner
    {
        $this->prompt = $prompt;
        return $this;
    }

    /**
     * Set the colour of prompt (if any) and the spinner, as with MacrameText
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function colour(String $colour):MacrameSpinner
    {
        $this->output->colour($colour);
        return $this;
    }

    /**
     * Synonym for colour()
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function color(String $colour):MacrameSpinner
    {
        return $this->colour($colour);
    }

    /**
     * Set the background colour of prompt (if any) and the spinner, as with MacrameText
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function backgroundColour(String $colour):MacrameSpinner
    {
        $this->output->backgroundColour($colour);
        return $this;
    }

    /**
     * Synonym for backgroundColour()
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function backgroundColor(String $colour):MacrameSpinner
    {
        return $this->backgroundColour($colour);
    }

    /**
     * Set the style of prompt (if any) and the spinner, as with MacrameText
     *
     * @param  String $prompt
     * @return MacrameSpinner
     */
    public function style(String $style):MacrameSpinner
    {
        $this->output->style($style);
        return $this;
    }

    /**
     * Display the spinner animation.
     *
     * @return void
     * @note   This runs an infinite loop. Be warned.
     */
    public function show():void
    {
        IO::hideCursor();
        while(true) {
            array_map(function($a) {
                IO::writeStdout($this->buildSpinnerText($a).$this->buildEraseSpinnerText());
                usleep($this->speed);
            }, $this->animation);
        }
        IO::showCursor();
    }

    /**
     * Run a user-provided function while displaying the spinner.
     *
     * @param  Callable $function Callable to run while spinner displayed
     * @param  Array    $args     Arguments for the callable as array
     * @return mixed
     */
    public function run(Callable $function, Array $args = [])
    {
        $pid = pcntl_fork();

        if($pid == -1) {
            $text = new Text("Could not fork child process");
            $text->error();
            return null;
        }

        if($pid) {
            $result = call_user_func_array($function, $args);
        }
        else {
            $this->show();
        }

        posix_kill($pid, SIGKILL);

        return $result;
    }

    /**
     * Compose and return the output consisting of the prompt and the
     * character of the spinner. Uses MacrameText to apply styling.
     *
     * @param String
     */
    private function buildSpinnerText(String $spinnerPart):String
    {
        return $this->output->text($this->prompt.$spinnerPart)->get();
    }

    /**
     * Compose and return a string of the correct number of <BACKSPACE>
     * characters to erase the output to the start of the line.
     *
     * @return String
     */
    private function buildEraseSpinnerText():String
    {
        return join(array_fill(0,strlen($this->animation[0]), BACKSPACE)).join(array_fill(0,strlen($this->prompt), BACKSPACE));
    }
}
