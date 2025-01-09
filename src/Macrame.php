<?php

namespace Gbhorwood\Macrame;

use Gbhorwood\Macrame\MacrameIO as IO;

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
    public function __construct(String $name = null)
    {
        pcntl_async_signals(true);

        if ($name) {
            cli_set_process_title($name);
        }

        register_shutdown_function(function () {
            self::shutdown();
        });

        pcntl_signal(SIGINT, fn () => self::shutdown());
        pcntl_signal(SIGTERM, fn () => self::shutdown());
        pcntl_signal(SIGHUP, fn () => self::shutdown());
        pcntl_signal(SIGUSR1, fn () => self::shutdown());
    }

    /**
     * Verify the system is capable of running Macrame.
     * die() on failure.
     *
     * @return void
     */
    public function preflight(): void
    {
        $phpversion_array = explode('.', phpversion());
        if ((int)$phpversion_array[0].$phpversion_array[1] < 74) {
            die('minimum php required is 7.4. exiting');
        }

        if (!extension_loaded('posix')) {
            die('posix required. exiting');
        }

        if (!extension_loaded('mbstring')) {
            die('mbstring required. exiting');
        }
    }

    /**
     * Determine if the command is running on the command line
     *
     * @return bool
     * @todo   Handle weirdness with cron
     */
    public function running(): bool
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * Creates and returns a MacrameArgs object for accessing a command line argument
     *
     * @param  String $argname The name of the argument
     * @return MacrameArgs
     */
    public function args(String $argname): MacrameArgs
    {
        return new MacrameArgs($argname);
    }

    /**
     * Creates and returns a MacrameText object for styling the string $text
     *
     * @param  ?String $text
     * @return MacrameText
     */
    public function text(String $text = null): MacrameText
    {
        return new MacrameText($text);
    }

    /**
     * Creates and returns a MacrameInput object
     *
     * @return MacrameInput
     */
    public function input(): MacrameInput
    {
        return new MacrameInput();
    }

    /**
     * Creates and returns a MacrameFile object for reading and writing files
     *
     * @param  String $path
     * @return MacrameFile
     */
    public function file(String $path): MacrameFile
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
    public function table(array $headers, array $data): MacrameTable
    {
        return new MacrameTable($headers, $data);
    }

    /**
     * Creates and returns a MacrameMenu object for menu input
     *
     * @return MacrameMenu
     */
    public function menu(): MacrameMenu
    {
        return new MacrameMenu();
    }

    /**
     * Creates and returns a MacrameSpinner object for creating spinners
     *
     * @param  String  $animation
     * @return MacrameSpinner
     */
    public function spinner(String $animation = null): MacrameSpinner
    {
        return new MacrameSpinner($animation);
    }

    /**
     * Creates and returns a MacrameFiglet object for creating headlines
     *
     * @param  String  $text
     * @return MacrameFiglet
     */
    public function figlet(String $text): MacrameFiglet
    {
        return new MacrameFiglet($text);
    }

    /**
     * Creates and returns a MacrameDownload object for downloading files
     *
     * @param  String  $url
     * @return MacrameDownload
     */
    public function download(String $url): MacrameDownload
    {
        return new MacrameDownload($url);
    }

    /**
     * Does cleanup and exits the script with 0
     *
     * @return Int
     */
    public static function shutdown()
    {
        IO::showCursor();
        self::unlinkFiles();
        exit(0);
    }

    /**
     * Does cleanup and exits the script with 0
     *
     * @return void
     */
    public function exit(): void
    {
        self::shutdown();
    }

    /**
     * Removes all files in the $toDelete array in MacrameFile
     * This is part of cleanup on exit.
     *
     * @return void
     */
    public static function unlinkFiles(): void
    {
        array_map(fn ($f) => @unlink($f), MacrameFile::$toDelete);
    }
}
