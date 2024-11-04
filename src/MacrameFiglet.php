<?php

namespace Gbhorwood\Macrame;

require __DIR__ . '/../vendor/autoload.php';

use Gbhorwood\Macrame\MacrameIO as IO;
use Gbhorwood\Macrame\MacrameText as Text;
use Povils\Figlet\Figlet;

/**
 * Handle headlines
 *
 */
class MacrameFiglet
{
    /**
     * The text to format and output
     * @var String
     * @access private
     */
    private String $headline;

    /**
     * The figlet object
     * @var Figlet
     * @access private
     */
    private Figlet $figlet;

    /**
     * Colour code for MacrameText
     * @var ?String
     * @access private
     */
    private ?String $colour = null;

    /**
     * Style code for MacrameText
     * @var bool
     * @access private
     */
    private bool $bold = false;

    /**
     * The list of available figlet fonts
     * @var Array<String>
     * @access private
     */
    private array $validFonts = [
        "3-d",
        "3d_diagonal",
        "5lineoblique",
        "alligator",
        "alligator2",
        "alpha",
        "amc3line",
        "amcaaa01",
        "amcneko",
        "amcrazor",
        "ansi_shadow",
        "ascii_new_roman",
        "avatar",
        "banner3-D",
        "banner4",
        "bell",
        "big",
        "block",
        "blocks",
        "braced",
        "bright",
        "broadway",
        "bubble",
        "bulbhead",
        "chiseled",
        "chunky",
        "colossal",
        "crawford",
        "cricket",
        "cyberlarge",
        "cybermedium",
        "cybersmall",
        "cygnet",
        "digital",
        "doh",
        "doom",
        "double",
        "epic",
        "fender",
        "filter",
        "flowerpower",
        "fuzzy",
        "georgi16",
        "Georgia11",
        "ghoulish",
        "goofy",
        "graceful",
        "graffiti",
        "impossible",
        "isometric1",
        "isometric2",
        "isometric3",
        "isometric4",
        "italic",
        "ivrit",
        "jacky",
        "keyboard",
        "larry3d",
        "lineblocks",
        "merlin1",
        "nvscript",
        "ogre",
        "os2",
        "puffy",
        "rammstein",
        "rectangles",
        "red_phoenix",
        "roman",
        "rounded",
        "rowancap",
        "rozzo",
        "script",
        "serifcap",
        "shadow",
        "shimrod",
        "slant",
        "small",
        "smallcaps",
        "smisome1",
        "smkeyboard",
        "smshadow",
        "smslant",
        "soft",
        "spliff",
        "s-relief",
        "standard",
        "starstrips",
        "starwars",
        "stop",
        "straight",
        "sub-zero",
        "swampland",
        "sweet",
        "thin",
        "tombstone",
        "twisted",
        "twopoint",
        "univers",
        "usaflag",
        "varsity",
    ];

    /**
     * Constructor
     *
     * @param  String $headline
     * @return void
     */
    public function __construct(String $headline)
    {
        // remove macrame formatting and line breaks so we can wrap and style
        $headline = MacrameText::stripFormatting($headline);
        $headline = str_replace(PHP_EOL, ' ', $headline);

        $this->headline = $headline;
        $this->figlet = new Figlet();
    }

    /**
     * Set headline font.
     * If font name is invalid, throw warning and use default.
     *
     * @param  String $name
     * @return MacrameFiglet
     */
    public function font(String $name): MacrameFiglet
    {
        if (!in_array($name, $this->validFonts)) {
            $warning = new MacrameText("'$name' is not a valid font");
            $warning->warning();
            return $this;
        }
        $this->figlet->setFont($name);
        return $this;
    }

    /**
     * Apply ANSI colour to $headline
     *
     * @param  String $colour
     * @return MacrameFiglet
     */
    public function colour(String $colour): MacrameFiglet
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * Alias of colour
     *
     * @param  String $colour
     * @return MacrameFiglet
     */
    public function color(String $colour): MacrameFiglet
    {
        return $this->colour($colour);
    }

    /**
     * Apply ANSI bold styling to $headline
     *
     * @return MacrameFiglet
     */
    public function bold(): MacrameFiglet
    {
        $this->bold = true;
        return $this;
    }

    /**
     * Write $headline to STDOUT
     *
     * @return void
     */
    public function write(): void
    {
        IO::writeStdout($this->get().PHP_EOL);
    }

    /**
     * Get $headline rendered as figlet and
     * wrapped as best as possible to terminal
     * width.
     *
     * @return String
     */
    public function get(): String
    {
        $wrappedLines = $this->wrap();

        $renderedLines = array_map(function ($l) {
            // render and remove residual delimiters
            $renderedLine = str_replace('#', ' ', $this->figlet->render($l));

            // remove blank lines. dependency figlet renderer includes many.
            $renderedLine = join(PHP_EOL, array_filter(explode(PHP_EOL, $renderedLine), fn ($l) => strlen(trim($l)) > 0));

            // apply styling
            $renderedLineText = new MacrameText($renderedLine);

            if ($this->bold) {
                $renderedLineText->style('bold');
            }

            if ($this->colour) {
                $renderedLineText->colour($this->colour);
            }

            return $renderedLineText->get();
        }, $wrappedLines);


        return join(PHP_EOL, array_filter($renderedLines));
    }

    /**
     * Get the headline as an array of lines wrapped to the width of the
     * terminal as determined by IO::getColWidth.
     *
     * @return Array<String>
     */
    public function wrap(): array
    {
        $text = new MacrameText();

        // Get headline as array of words
        $headlineWords = array_map(fn ($l) => $l.' ', explode(' ', $this->headline));

        /**
         * Function to get width of longest line in the figlet render of one word. Figlet words have more than one line.
         */
        $maxWidth = fn ($word) => max(array_map(fn ($w) => $text->mb_strwidth_ansi($w), explode(PHP_EOL, $this->figlet->render($word))));

        // Build array of lines wrapped to terminal width
        $wrappedLines = [];
        $terminalWidth = $this->getTerminalWidth();
        $line = '';
        $acc = 0;
        foreach ($headlineWords as $word) {
            $wordWidth = $maxWidth($word);
            if (($acc + $wordWidth) <= $terminalWidth) {
                $acc += $wordWidth;
                $line .= $word;
            } else {
                $wrappedLines[] = $line;
                $line = $word;
                $acc = 0;
            }
        }
        $wrappedLines[] = rtrim($line);

        return array_filter($wrappedLines);
    }

    public function getTerminalWidth(): Int
    {
        return IO::getColWidth();
    }
}
