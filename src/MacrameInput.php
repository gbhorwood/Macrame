<?php
namespace Gbhorwood\Macrame;

use \Gbhorwood\Macrame\MacrameIO as IO;

/**
 * Handle user input
 *
 */
class MacrameInput
{
    /**
     * List of validation functions
     * @var Array<callable>
     * @access private
     */
    private Array $validators = [];

    /**
     * MacrameText object
     * @var MacrameText
     * @access private
     */
    private MacrameText $text;

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
     * Add a validator function for min length
     * to list of validators to run against the input
     *
     * @param  Int    $min The minimum mb string length of the input
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isLengthMin(Int $min, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsLengthMin($min, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function for max length
     * to list of validators to run against the input
     *
     * @param  Int    $max The maximum mb string length of the input
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isLengthMax(Int $max, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsLengthMax($max, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function for a preg expression match
     * to list of validators to run against the input
     *
     * @param  String $expression The expression for preg_match
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isPregMatch(String $expression, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsPregMatch($expression, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is equal to a value
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isEqualTo(String $validValue, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsEqualTo($validValue, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is one of an array of values
     * to list of validators to run against the input
     *
     * @param  Array<String> $validList List of values to validate against
     * @param  String        $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isOneOf(Array $validList, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsOneOf($validList, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is of a minimum entropy
     * to list of validators to run against the input
     *
     * @param  Float  $entropyMin   The minimum entropy       
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isEntropyMin(Float $entropyMin, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsEntropyMin($entropyMin, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value contains a substring
     * to list of validators to run against the input
     *
     * @param  String $substring    The substring the value must contain
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function doesContain(String $substring, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionDoesContain($substring, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value does not contain a substring
     * to list of validators to run against the input
     *
     * @param  String $substring    The substring the value must not contain
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function doesNotContain(String $substring, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionDoesNotContain($substring, $errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is an integer
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isInt(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsInt($errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is an integer or float
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isNumber(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsNumber($errorMessage);
        return $this;
    }

    /**
     * Add a validator function for email
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isEmail(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsEmail($errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is a valid url
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isUrl(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsUrl($errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is a valid ip adress
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isIpAddress(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsIpAddress($errorMessage);
        return $this;
    }

    /**
     * Add a validator function to test if value is a valid date in any format
     * to list of validators to run against the input
     *
     * @param  String $errorMessage The optional message to display if validation fails
     * @return MacrameInput
     */
    public function isDate(String $errorMessage = null):MacrameInput
    {
        $this->validators[] = $this->validator()->functionIsDate($errorMessage);
        return $this;
    }

    /**
     * Add a custom validator function to the validator list.
     *
     * @param  callable $validator The validator function
     * @return MacrameInput
     */
    public function addValidator(callable $validator, String $errorMessage = null):MacrameInput
    {
        $this->validators[] = function(String $value) use($validator, $errorMessage) {
            if(!$validator($value)) {
                MacrameValidator::displayError($errorMessage);
                return false;
            }
            return true;
        };
        return $this;
    }

    /**
     * Continue reading a line of data from the user with optional $prompt
     * displayed until all validations, if any, pass.
     *
     * @param  String        $prompt The optional prompt to show the user
     * @param  Array<String> $tabCompletions An optional array of words that can be completed by tabbing
     * @return String The user input
     */
    public function readline(String $prompt = null, Array $tabCompletions = []):String
    {
        readline_completion_function(fn($line, $index) => $tabCompletions);

        do {
            $input = trim(readline($prompt));
            readline_add_history($input);
        }
        while(!$this->isValid($input));

        return $input;
    }

    /**
     * Reads one line of of user input data, echoing '*' in place of each character.
     *
     * @param  String $prompt The prompt to display. Default 'password: '
     * @return String The user input
     */
    public function readPassword(String $prompt = 'password: '):String
    {
        /**
         * Function to poll for user password input
         */
        $pollForPassword = function(String $prompt) {
            // suppress echo
            readline_callback_handler_install("", function(){});

            // array of characters of the password
            $passwordCharArray = [];

            IO::writeStdout($prompt);

            // accept and handle each user keystroke until <RETURN>
            while(true) {
                $keystroke = IO::keyStroke();

                // handle <return>
                if (ord($keystroke) == 10) {
                    IO::writeStdout(PHP_EOL);
                    break;
                }
                // handle <backspace>
                elseif (ord($keystroke) == 127) {
                    array_pop($passwordCharArray);
                    IO::backspace();
                    IO::eraseToEndOfLine();
                }
                // log char, echo dot.
                else {
                    $passwordCharArray[] = $keystroke;
                    IO::writeStdout('*');
                }
            }

            // reinstall echo
            readline_callback_handler_remove();

            return join($passwordCharArray);
        };

        /**
         * Call $pollForPassword until validations pass
         */
        do {
            $input = $pollForPassword($prompt);
        }
        while(!$this->isValid($input));

        return $input;
    }

    /**
     * Reads content piped in on STDIN
     *
     * @return ?String The content from STDIN, if any
     */
    public function readPipe():?String
    {
        return IO::getPipedContent();
    }

    /**
     * Iterates over content piped in on STDIN
     *
     * @return \Iterator
     */
    public function readPipeByLine():\Iterator
    {
        return IO::getPipedContentGenerator();
    }

    /**
     * Reads and returns one keystroke. Accepts and displayes option prompt.
     *
     * @param  ?String $prompt  The optional prompt to display
     * @return String
     */
    public function readKey(?String $prompt = null):String
    {
        do {
            if($prompt) {
                IO::writeStdout($prompt);
            }
            $input = IO::keyStroke();
            IO::newline();
        }
        while(!$this->isValid($input));
        return $input;
    }

    /**
     * Reads one keydown stroke validated against a list of $options
     *
     * @param  Array<String> $options  The list of valid option characters
     * @param  ?String $default  The option returned if <RETURN> is hit
     * @param  ?String $prompt   The optional prompt to display 
     * @param  ?String $error    The optional message to display if validation fails
     */
    public function readOption(Array $options, ?String $default = null, ?String $prompt = null, ?String $error = null):String
    {
        $validOptions = array_unique(array_filter(array_merge(array_map(fn($o) => $o[0], $options), [@$default[0]])));
        $this->isOneOf($default ? array_merge($validOptions, [chr(10)]) : $validOptions, $error);
        $key =  $this->readKey($prompt);
        return $key == chr(10) ? @$default[0] : $key;
    }

    /**
     * Run all validators against $text, return false if any fail.
     *
     * @param  String $text The text to validate
     * @return bool False if one or more validators fails
     */
    private function isValid(String $text):bool
    {
        return count($this->validators) > 0 ? !in_array(false, array_map(fn($v) => $v($text), $this->validators)) : true;
    }

    /**
     * Build and return a MacrameValidator object
     *
     * @return MacrameValidator
     */
    private function validator():MacrameValidator
    {
        return new MacrameValidator();
    }
}
