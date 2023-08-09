<?php
namespace Gbhorwood\Macrame;

/**
 * Handle file read and write
 *
 */
class MacrameFile
{
    /**
     * The path to the file as supplied
     * @var String
     * @access private
     */
    private String $path;

    /**
     * Constructor
     *
     * @param  String $path
     * @return void
     */
    public function __construct(String $path)
    {
        $this->path = $path;
    }


    public function byLine():\Generator
    {
        if(!$this->readable()) {
            $warning = new MacrameText('Cannot read file at '.$this->path);
            $warning->warning();
            yield;
        }

        $fp = fopen($this->handleTilde($this->path), 'r');

        while(!feof($fp)) {
            yield fgets($fp);
        }

        fclose($fp);
    }

    /**
     * Determines if the $path file exists and is readable
     *
     * @return bool
     */
    public function readable():bool
    {
        return file_exists($this->handleTilde($this->path)) && is_readable($this->handleTilde($this->path));
    }

    /**
     * Determines if the $path file is writable
     *
     * @return bool
     */
    public function writable():bool
    {
        // file exists
        if(file_exists($this->handleTilde($this->path))) {
            return is_writable($this->handleTilde($this->path));
        }

        // file does not exist. intention is to create.
        return file_exists(dirname($this->handleTilde($this->path))) &&
            is_writable(dirname($this->handleTilde($this->path)));
    }

    /**
     * Determines if a file already exists.
     *
     * @return bool
     */
    public function clobbers():bool
    {
        return file_exists($this->handleTilde($this->path));
    }

    /**
     * Handles substition of the path to the user's home directory for ~
     * as would happen in bash or any other modern shell.
     *
     * @param  String $path The user-supplied path 
     * @return String
     * @note   Requires posix
     */
    private function handleTilde(String $path):String
    {
        return substr(trim($path), 0, 1) == '~' ? posix_getpwuid(posix_getuid())['dir'].substr(trim($path),1) : trim($path);
    }
}
