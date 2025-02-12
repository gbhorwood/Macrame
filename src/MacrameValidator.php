<?php

namespace Gbhorwood\Macrame;

use Gbhorwood\Macrame\MacrameIO as IO;

/**
 * Handle validation of user data
 *
 */
class MacrameValidator
{
    /**
     * Returns a function that accepts a value and tests if
     * its length is greater than or equal to $min.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  Int    $min The minimum number of utf-8 characters
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsLengthMin(Int $min, String $error = null): callable
    {
        return function (String $value) use ($min, $error): bool {
            if (mb_strlen($value) < $min) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * its length is less than or equal to $min.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  Int    $max The maximum number of utf-8 characters
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsLengthMax(Int $max, String $error = null): callable
    {
        return function (String $value) use ($max, $error): bool {
            if (mb_strlen($value) > $max) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it matches the passed expressoin
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $expression The expression for preg_match
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsPregMatch(String $expression, String $error = null): callable
    {
        return function (String $value) use ($expression, $error): bool {
            if (preg_match($expression, $value) === 0) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is one of the elements of $validList
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $validValue The value $value must be equal to
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsEqualTo(String $validValue, String $error = null): callable
    {
        return function (String $value) use ($validValue, $error): bool {
            if (trim($validValue) !== trim($value)) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is one of the elements of $validList
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  Array<String>  $validList The array of valid values
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsOneOf(array $validList, String $error = null): callable
    {
        return function (String $value) use ($validList, $error): bool {
            if (!in_array($value, $validList)) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a string that equates to an integer
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsInt(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (!filter_var($value, FILTER_VALIDATE_INT) && $value != '0') {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a string that equates to either an int or floating point number
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsNumber(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (!filter_var($value, FILTER_VALIDATE_FLOAT) && $value != "0") {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a valid email.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsEmail(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a valid url, ie 'https://example.ca'.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsUrl(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a valid ip address.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsIpAddress(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is a valid date.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $error The optional error message to display if the validation fails
     * @return callable The validation function
     */
    public function functionIsDate(String $error = null): callable
    {
        return function (String $value) use ($error): bool {
            if (strtotime($value) === false) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it is of a minimum entropy.
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  Float $entropy The minimum entropy
     * @return callable The validation function
     */
    public static function functionIsEntropyMin(Float $entropy, String $error = null): callable
    {
        return function (String $value) use ($entropy, $error): bool {
            $calculated = array_reduce(count_chars($value, 1), fn ($t, $c) => $t + abs(($c / mb_strlen($value)) * log(($c / mb_strlen($value))) / log(2)));
            if ($calculated < $entropy) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it contains a substring
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $substring The substring the value must contain
     * @return callable The validation function
     */
    public static function functionDoesContain(String $substring, String $error = null): callable
    {
        return function (String $value) use ($substring, $error): bool {
            if (mb_strpos($value, $substring) === false) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Returns a function that accepts a value and tests if
     * it does not contain a substring
     * Returns true on pass, false on fail. Displays error message
     *
     * @param  String $substring The substring the value must not contain
     * @return callable The validation function
     */
    public static function functionDoesNotContain(String $substring, String $error = null): callable
    {
        return function (String $value) use ($substring, $error): bool {
            if (mb_strpos($value, $substring) !== false) {
                self::displayError($error);
                return false;
            }
            return true;
        };
    }

    /**
     * Writes $message as error to STDERR if it exists
     *
     * @param  ?String $message The error message to print on STDERR
     * @return void
     */
    public static function displayError(?String $message = null): void
    {
        if(!is_null($message)) {
            $output = new \Gbhorwood\Macrame\MacrameText($message);
            $output->error();
        }
    }
}
