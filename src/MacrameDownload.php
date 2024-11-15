<?php

namespace Gbhorwood\Macrame;

if (!defined('BACKSPACE')) {
    define('BACKSPACE', chr(8));
}

use Gbhorwood\Macrame\MacrameIO as IO;

/**
 * Handle downloading files
 *
 */
class MacrameDownload
{
    /**
     * The url of the file
     * @var String
     * @access private
     */
    private String $url;

    /**
     * Optional basic auth username
     * @var ?String
     * @access private
     */
    private ?String $username = null;

    /**
     * Optional basic auth password
     * @var ?String
     * @access private
     */
    private ?String $password = null;

    /**
     * The size of the file in bytes
     * @var ?Int
     * @access private
     */
    private ?Int $size = null;

    /**
     * Optional output file
     * @var ?MacrameFile
     * @access private
     */
    private ?MacrameFile $out = null;

    /**
     * Character to use as unit of the progress bar
     * @var String
     * @access private
     */
    private String $progressChar = '#';

    /**
     * If the download is successful
     * @var bool
     * @access private
     */
    private bool $succeeded = false;

    /**
     * Constructor
     *
     * @param  String $url
     * @return void
     */
    public function __construct(String $url)
    {
        $this->url = $url;
    }

    /**
     * Set username and password for basic auth
     *
     * @param  String $username
     * @param  String $password
     * @return MacrameDownload
     */
    public function auth(String $username, String $password): MacrameDownload
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    /**
     * Set the output file. If $path is a directory, the filename from the url will
     * be used to complete the path.
     *
     * @return MacrameDownload
     */
    public function out(String $path): MacrameDownload
    {
        $path = trim($path);
        if (is_dir($path)) {
            $path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.basename($this->url);
        }

        if (substr($path, -1) == DIRECTORY_SEPARATOR) {
            $path = $path.basename($this->url);
        }

        $this->out = new MacrameFile($path);
        return $this;
    }

    /**
     * Alias of out()
     *
     * @return MacrameDownload
     */
    public function outfile(String $path): MacrameDownload
    {
        return $this->out($path);
    }

    /**
     * Set the character to use for the progress bar
     *
     * @param  String $progressChar
     * @return MacrameDownload
     */
    public function progressChar(String $progressChar): MacrameDownload
    {
        $this->progressChar = $progressChar;
        return $this;
    }

    /**
     * Get size of remote file in bytes
     *
     * @return Int
     * @see getRemoteSize
     */
    public function size(): Int
    {
        return $this->getRemoteSize();
    }

    /**
     * Get the outfile path
     *
     * @return MacrameFile
     */
    public function getOut(): MacrameFile
    {
        if (!$this->out) {
            $this->setOut();
        }
        return $this->out;
    }

    /**
     * Alias of getOut()
     *
     * @return MacrameFile
     */
    public function getOutfile(): MacrameFile
    {
        return $this->getOut();
    }

    /**
     * Did the download succeed
     *
     * @return bool
     */
    public function succeeded(): bool
    {
        return $this->succeeded;
    }

    /**
     * Download file from $url to $out file, display progress.
     *
     * @return MacrameDownload
     */
    public function get(): MacrameDownload
    {
        // set default out file if none set by user
        if (!$this->out) {
            $this->setOut();
        }

        // validate default outfile can be written to
        if (!$this->validateOut()) {
            return $this;
        }

        // fork
        $pid = pcntl_fork();

        // fork failed
        if ($pid == -1) {
            $this->error("Download error. Could not fork child process.");
        }

        // run download and progress display in different processes
        if ($pid == 0) {
            $this->download();
            exit(0);
        } else {
            $this->showProgress();
        }

        $this->succeeded = is_file($this->out->path());

        return $this;
    }

    /**
     * Shows the progress display
     *
     * @return bool
     */
    private function showProgress(): bool
    {
        if ($this->getRemoteSize() == 0) {
            return false;
        }

        /**
         * Display progress header line. Format:
         * Progress <filename> <complete percent>: <progress bar>
         *
         * @param  Int $destSize
         * @param  Int $sourceSize
         * @param  bool $final If true, display 'done' instead of progress bar
         * @return Int The width of the display output, used for erasing
         */
        $showDisplay = function (Int $destSize, Int $sourceSize, bool $final = false): Int {

            $header = "Downloading ".basename($this->out->path())." ".number_format(($destSize / $sourceSize) * 100, 2).'% ';

            if ($final) {
                $bar = "done.".PHP_EOL;
            } else {
                $bar = join(array_fill(0, intval(ceil((IO::getColWidth() - mb_strwidth($header)) * ($destSize / $sourceSize)) / mb_strwidth($this->progressChar)), $this->progressChar));
            }
            $display = $header.$bar;

            IO::writeStdout($display);
            return mb_strwidth($display);
        };

        /**
         * Erase progress header line created by $showDisplay
         *
         * @param  Int $displaySize
         * @param  bool $final
         * @return void
         */
        $eraseDisplay = function (Int $displaySize, bool $final = false): void {
            IO::writeStdout(join(array_fill(0, $displaySize, BACKSPACE)));
            if ($final) {
                IO::writeStdout(LINE_ERASE_TO_END);
            }
        };

        while (true) {
            // php caches file stats. turn off for accuracy
            clearstatcache();

            // get size of source file and size of destination file at this moment in bytes
            $destSize = @filesize($this->out->path());
            $sourceSize = $this->getRemoteSize();

            // output the progress header line
            $displaySize = $showDisplay($destSize, $sourceSize);

            // if destination file is the size of the source file, complete
            if ($destSize >= $sourceSize) {
                $eraseDisplay($displaySize, true);
                $showDisplay($destSize, $sourceSize, true).PHP_EOL;
                return true;
            }

            // sleep for animation rate
            usleep(30000);

            $eraseDisplay($displaySize);
        }

    }

    /**
     * Download file at $url to $path via curl
     *
     * @return bool
     */
    private function download(): bool
    {
        if ($this->getRemoteSize() == 0) {
            return false;
        }

        $fp = fopen($this->out->path(), 'w');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }
        $res = curl_exec($ch);

        return true;
    }

    /**
     * Validate output file path set in $out. On pass, returns true.
     * On any failure, output error and return false.
     *
     * @return bool
     */
    private function validateOut(): bool
    {
        if (!$this->out) {
            return true;
        }

        // directory of out path exists
        if (!is_dir(dirname($this->out->path()))) {
            $this->error("Download error. Invalid directory at ".dirname($this->out->path()));
            return false;
        }

        // outpath is writable
        if (!$this->out->writable()) {
            $this->error("Download error. Cannot write to target file at ".$this->out->path());
            return false;
        }

        // file at outpath does not already exist
        if ($this->out->clobbers()) {
            $this->error("Download error. Target file already exists at ".$this->out->path());
            return false;
        }

        // there is enough space on outpath's partition
        if (!@$this->out->enoughSpace($this->getRemoteSize())) {
            $this->error("Download error. Not enough space on device");
            return false;
        }

        return true;
    }

    /**
     * Set the default outfile to the current directory and the file
     * name from the url
     *
     * @return void
     */
    private function setOut(): void
    {
        $this->out = new MacrameFile(getcwd().DIRECTORY_SEPARATOR.basename($this->url));
    }

    /**
     * Get size of file at url in bytes from Content-Length header
     *
     * @return Int
     */
    private function getRemoteSize(): Int
    {
        if (isset($this->size)) {
            return $this->size;
        }

        // timeout in seconds
        $timeout = 5;

        // get headers from url request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }
        $res = curl_exec($ch);

        // curl error
        if ($res === false) {
            $this->error('Remote file is unreachable');
            $this->size = 0;
            return 0;
        }

        // get file size in bytes from 'Content-Length' header as int.
        $contentLenghtLines = array_values(array_filter(explode(PHP_EOL, $res), fn ($n) => str_starts_with(strtolower($n), 'content-length:')));
        $contentLengths = array_map(fn ($n) => intval(trim(explode(' ', trim($n))[1])), $contentLenghtLines);
        rsort($contentLengths);
        $contentLength = $contentLengths[0] ?? 0;

        // handle zero file size
        if ($contentLength == 0) {
            $this->error('Remote file does not exist');
            $this->size = 0;
            return 0;
        }

        $this->size = $contentLength;
        return $contentLength;
    }

    /**
     * Outputs an error
     *
     * @param  String $error
     * @return void
     */
    private function error(String $error): void
    {
        $error = new MacrameText($error);
        $error->error();
    }
}
