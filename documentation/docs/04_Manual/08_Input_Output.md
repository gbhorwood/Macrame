Macrame provides several functions as static methods in the `MacrameIO` class for low-level control of terminal input and output. These methods are primarily intended for internal use by other Macrame functions, but can be accessed by users.

In general, it is recommended to use Macrame's `text()` and `input()` functions instead of these. For instance, to read piped input from `STDIN`, prefer `$macrame->input()->readPipe()` to `IO::getPipedContent()`.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
</div>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            These functions are typically for internal use by Macrame.
        </div>
    </span>
</div>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            All functions in `IO` are static methods of the `MacrameIO` class.
        </div>
    </span>
</div>

# Quickref
```PHP
use \Gbhorwood\Macrame\MacrameIO as IO;

// string. Returns data piped into the script on STDIN
IO::getPipedContent()

// iterator. Returns data piped into the script on STDIN as an iterator
IO::getPipedContentGenerator()

// bool. Returns true if data has been piped to the script on STDIN
IO::pipedContentExists()

// void. Write string to STDOUT
IO::writeStdout("foo");

// void. Write string to STDERR
IO::writeStderr("foo");

// void. Erase current line, set cursor to beginning of line
IO::eraseLine();

// void. Erase the previous n lines, set cursor to begining of line
IO::eraseLines(3);

// void. hide cursor
IO::hideCursor();

// void. show cursor
IO::showCursor();

// void. Move character back one space on current line as <BACKSPACE>
IO::backspace();

// void. Delete all output on current line from cursor position to end of line
IO::eraseToEndOfLine()

// void. Clear screen and home cursor to top
IO::clearScreen()

// string. Read exactly one keystroke and return
IO::keyStroke

// void. Output a newline
IO::newline()

// int. Return the column width of the terminal in characters for wrapping text. This is not the same as the absolute col width.
IO::getColWidth()

// int. Return the height of the terminal in characters
IO::getRowHeight()
```

