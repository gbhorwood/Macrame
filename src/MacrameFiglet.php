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
        "1row", "3d_diagonal", "3-d", "3x5", "4max", "5lineoblique", "acrobatic", "alligator2", "alligator3", "alligator", "alphabet", "alpha", "amc3line",
        "amc3liv1", "amcaaa01", "amcneko", "amcrazo2", "amcrazor", "amcslash", "amcslder", "amcthin", "amctubes", "amcun1", "ansi_shadow", "arrows", "ascii_new_roman",
        "avatar", "B1FF", "banner3-D", "banner3", "banner4", "banner", "barbwire", "basic", "bear", "bell", "benjamin", "bigchief", "bigfig", "big", "binary", "block",
        "blocks", "bolger", "braced", "bright", "broadway", "broadway_kb", "bubble", "bulbhead", "calgphy2", "caligraphy", "cards", "catwalk", "chiseled", "chunky",
        "coinstak", "cola", "colossal", "computer", "contessa", "contrast", "cosmic", "cosmike", "crawford", "crazy", "cricket", "cyberlarge", "cybermedium", "cybersmall",
        "cygnet", "DANC4", "dancingfont", "decimal", "defleppard", "diamond", "dietcola", "digital", "doh", "doom", "dosrebel", "dotmatrix", "double", "doubleshorts",
        "drpepper", "dwhistled", "eftichess", "eftifont", "eftipiti", "eftirobot", "eftitalic", "eftiwall", "eftiwater", "epic", "fender", "files.txt", "filter",
        "fire_font-k", "fire_font-s", "flipped", "flowerpower", "fourtops", "fraktur", "funface", "funfaces", "fuzzy", "georgi16", "Georgia11", "ghost", "ghoulish",
        "glenyn", "goofy", "gothic", "graceful", "gradient", "graffiti", "greek", "heart_left", "heart_right", "henry3d", "hex", "hieroglyphs", "hollywood", "horizontalleft",
        "horizontalright", "ICL-1900", "impossible", "invita", "isometric1", "isometric2", "isometric3", "isometric4", "italic", "ivrit", "jacky", "jazmine", "jerusalem",
        "katakana", "kban", "keyboard", "knob", "konto", "kontoslant", "larry3d", "lcd", "lean", "letters", "lildevil", "lineblocks", "linux", "lockergnome", "madrid",
        "marquee", "maxfour", "merlin1", "merlin2", "mike", "mini", "mirror", "mnemonic", "modular", "morse2", "morse", "moscow", "mshebrew210", "muzzle", "nancyj-fancy",
        "nancyj", "nancyj-improved", "nancyj-underlined", "nipples", "nscript", "ntgreek", "nvscript", "o8", "octal", "ogre", "oldbanner", "os2", "pawp", "peaks", "peaksslant",
        "pebbles", "pepper", "poison", "puffy", "puzzle", "pyramid", "rammstein", "rectangles", "red_phoenix", "relief2", "relief", "reverse", "roman", "rot13", "rotated",
        "rounded", "rowancap", "rozzo", "runic", "runyc", "santaclara", "sblood", "script", "serifcap", "shadow", "shimrod", "short", "slant", "slide", "slscript", "smallcaps",
        "small", "smisome1", "smkeyboard", "smpoison", "smscript", "smshadow", "smslant", "smtengwar", "soft", "speed", "spliff", "s-relief", "stacey", "stampate", "stampatello",
        "standard", "starstrips", "starwars", "stellar", "stforek", "stop", "straight", "sub-zero", "swampland", "swan", "sweet", "tanja", "tengwar", "term", "test1", "thick",
        "thin", "threepoint", "ticks", "ticksslant", "tiles", "tinker-toy", "tombstone", "train", "trek", "tsalagi", "tubular", "twisted", "twopoint", "univers", "usaflag",
        "varsity", "wavy", "weird", "wetletter", "whimsy", "wow"
    ];

    /**
     * Constructor
     *
     * @param  String $headline
     * @return void
     */
    public function __construct(String $headline)
    {
        $text = new MacrameText($headline);
        $headline = $text->stripFormatting($headline);
        $headline = str_replace(PHP_EOL, ' ', $headline);
        $this->headline = $headline;

        $this->figlet = new Figlet();
    }

    /**
     * Set headline font
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
        IO::writeStdout($this->get());
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
            $renderedLine = str_replace('#', ' ', $this->figlet->render($l));
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

        return join(PHP_EOL, $renderedLines);
    }

    /**
     * Get the headline as an array of lines wrapped to the width of the
     * terminal as determined by IO::getColWidth.
     *
     * @return Array<String>
     */
    private function wrap(): array
    {
        $text = new MacrameText();

        // Get headline as array of words
        $headlineWords = array_map(fn ($l) => $l.' ', explode(' ', $this->headline));

        /**
         * Function to get width of longest line in the figlet render of a word.
         */
        $maxWidth = fn ($word) => max(array_map(fn ($w) => $text->mb_strwidth_ansi($w), explode(PHP_EOL, $this->figlet->render($word))));

        // Build array of lines wrapped to width
        $wrappedLines = [];
        $terminalWidth = IO::getColWidth();
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

        return $wrappedLines;
    }
}
