This recipe covers outputting the contents of a large text file to screen as pages. After every page, the user is prompted to input either `<SPACE>` for the next page, `<RETURN>` for the next line or `q` to quit.

# Features used
This recipie uses the following Macrame features:
* [text](../04_Manual/03_Styled_Text_Output.md)
* [file](../04_Manual/06_File_Read_and_Write.md)

# Example
```PHP
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

$macrame = new Macrame("page a file");

if($macrame->running()) {

    /**
     * Read file using file()->read() then output using text()->write()
     */
    $macrame->text($macrame->file('/path/to/file')->read())->page();

}
```

# Walkthrough
There are two components to this script. The first is reading in the contents of the text file with `file()->read()`. This returns a string which can be used to create a `text()` object.

The second component is outputting the string using the `page()` method of `text`. Note that `wrap()` is applied here to ensure that string is formatted to fit the width of the terminal.
