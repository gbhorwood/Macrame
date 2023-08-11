<?php
namespace Gbhorwood\Macrame;

/**
 * Handle user input
 *
 */
class MacrameInput
{
    private Array $validators = [];

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
     * Continue reading a line of data from the user with optional $prompt
     * displayed until all validations, if any, pass.
     *
     * @param  String $prompt The optional prompt to show the user
     * @param  Array  $tabCompletions An optional array of words that can be completed by tabbing
     * @return String The user input
     */
    public function readline(String $prompt = null, Array $tabCompletions = []):String
    {
        readline_completion_function(fn($line,$index) => $tabCompletions);

        do {
            $input = trim(readline($prompt));
            readline_add_history($input);
        }
        while(!$this->isValid($input));

        return $input;
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
