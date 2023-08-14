<?php
namespace Gbhorwood\Macrame;

/**
 * Tell phpunit when using processIsolation what STDIN is
 */
if(!defined('STDIN')) define('STDIN', fopen("php://stdin","r"));

/**
 * Create command line applications in php
 *
 */
class Macrame 
{
    /**
     * Constructor
     *
     * @param  String $name The name of the process to display in ps(1)
     */
    public function __construct(String $name=null)
    {
        if($name) {
            cli_set_process_title($name);
        }
    }

    /**
     * Verify the system is capable of running Macrame.
     * die() on failure.
     *
     * @return void
     */
    public function preflight():void
    {
        $phpversion_array = explode('.', phpversion());
        if ((int)$phpversion_array[0].$phpversion_array[1] < 74) {
            die('minimum php required is 7.4. exiting');
        }

        if(!extension_loaded('posix')) {
            die('posix required. exiting');
        }

        if(!extension_loaded('mbstring')) {
            die('mbstring required. exiting');
        }
    }

    /**
     * Determine if the command is running on the command line
     *
     * @return bool
     * @todo   Handle weirdness with cron
     */
    public function running():bool
    {
        if(PHP_SAPI == 'cli') {
            return true;
        }
        return false;
    }

    /**
     * Creates and returns a MacrameArgs object for accessing a command line argument
     *
     * @param  String $argname The name of the argument
     * @return MacrameArgs
     */
    public function args(String $argname):MacrameArgs
    {
        return new MacrameArgs($argname);
    }

    /**
     * Creates and returns a MacrameText object for styling the string $text
     *
     * @param  ?String $text
     * @return MacrameText
     */
    public function text(String $text = null):MacrameText
    {
        return new MacrameText($text);
    }

    /**
     * Creates and returns a MacrameInput object
     *
     * @return MacrameInput
     */
    public function input():MacrameInput
    {
        return new MacrameInput();
    }

    /**
     * Creates and returns a MacrameFile object for reading and writing files
     *
     * @param  String $path
     * @return MacrameFile
     */
    public function file(String $path):MacrameFile
    {
        return new MacrameFile($path);
    }

    /**
     * Creates and returns a MacrameTable object for creating tables
     *
     * @param  Array<String> $headers
     * @param  Array<String> $data
     * @return MacrameTable
     */
    public function table(Array $headers, Array $data):MacrameTable
    {
        return new MacrameTable($headers, $data, new MacrameText());
    }
}






