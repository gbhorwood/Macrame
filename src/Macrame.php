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
     * @param  String $text
     * @return MacrameText
     */
    public function text(String $text):MacrameText
    {
        return new MacrameText($text);
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






