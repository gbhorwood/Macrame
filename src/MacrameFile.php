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
     * Array of file paths to delete on shutdown
     *
     * @var Array<String>
     */
    public static Array $toDelete;

    /**
     * Constructor
     *
     * @param  String $path
     * @return void
     */
    public function __construct(String $path)
    {
        clearstatcache(true);
        $this->path = $path;
    }

    /**
     * Creates an empty file the object's file path with permissions 0755.
     * Displays warnings if file cannot be created due to permissions or
     * bad structure. Expands ~ to path to home dir.
     *
     * @return MacrameFile
     */
    public function create():MacrameFile
    {
        $path = $this->handleTilde($this->path);
        if(!file_exists($path)) {
            $mkdirWorked = file_exists(dirname($path)) ? true : @mkdir(dirname($path), 0755, true);
            $touchWorked = @touch($path);
            if(!$mkdirWorked || !$touchWorked) {
                $this->warn("Could not create file at $path");
            }
        }
        return $this;
    }

    /**
     * Return contents of the file as string
     *
     * @return ?String
     */
    public function read():?String
    {
        if(!$this->readable()) {
            $this->warn('Cannot read file at '.$this->path);
            return null;
        }

        return file_get_contents($this->handleTilde($this->path));
    }

    /**
     * Reads the file using a generator.
     * Displays warning on permissions errors.
     *
     * @return \Generator
     */ 
    public function byLine():\Generator
    {
        if(!$this->readable()) {
            $this->warn('Cannot read file at '.$this->path);
            yield;
        }
        else {
            $fp = fopen($this->handleTilde($this->path), 'r');

            while(!feof($fp)) {
                yield fgets($fp);
            }

            fclose($fp);
        }
    }

    /**
     * Write the string $text to the file.
     * Displays warnings on access or disk space errors.
     * Return true on success.
     *
     * @param  String $text  The text to write to file
     * @return bool
     */
    public function write(String $text):bool
    {
        // ensure file exists
        $this->create();

        // test permissions
        if(!$this->writable()) {
            $this->warn('Cannot write to file at '.$this->path);
            return false;
        }

        // test disk space
        if(!$this->enoughSpace($text)) {
            $this->warn('Note enough space on device to write to '.$this->path);
            return false;
        }

        // write to file
        $fp = fopen($this->handleTilde($this->path), 'w');
        fwrite($fp, $text);
        fclose($fp);

        return true;
    }

    /**
     * Determines if the file exists and is readable
     *
     * @return bool
     */
    public function readable():bool
    {
        return file_exists($this->handleTilde($this->path)) && is_readable($this->handleTilde($this->path));
    }

    /**
     * Determines if the file is writable
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
     * Synonym for clobbers()
     *
     * @return bool
     */
    public function exists():bool
    {
        return $this->clobbers();
    }

    /**
     * Sets this file to be deleted on script termination.
     *
     * @return MacrameFile
     * @see    Macrame::exit()
     */
    public function deleteOnQuit():MacrameFile
    {
        self::$toDelete[] = $this->path;
        return $this;
    }

    /**
     * Synonym for deleteOnQuit
     *
     * @return MacrameFile
     * @see    Macrame::exit()
     */
    public function deleteOnExit():MacrameFile
    {
        return $this->deleteOnQuit();
    }

    /**
     * Count the number of bytes in a given string of any encoding.
     * Used for calculating disk space requirements.
     *
     * @param  String $text The text to get the bytes of
     * @return Int The number of bytes
     */
    public function byteCount(String $text):Int
    {
        return mb_strlen($text, '8bit');
    }

    /**
     * Tests if there is enough space on the disk partition of the target
     * file for the string $text. 
     *
     * @param  String $text The text to write to file
     * @return bool If there is enough space
     */
    public function enoughSpace(String $text):bool
    {
        return disk_free_space(dirname($this->handleTilde($this->path))) > $this->byteCount($text);
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

    /**
     * Outputs a warning
     *
     * @param  String $warning
     * @return void
     */
    private function warn(String $warning):void
    {
        $warning = new MacrameText($warning);
        $warning->warning();
    }
}
