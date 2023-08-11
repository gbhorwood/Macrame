This example builds a very simple version of [`grep`](https://man7.org/linux/man-pages/man1/grep.1.html). It accepts the path to the file to search as an argument and an arbitrary number of search terms as arguments. It outputs lines that contain one or more of the search terms (no regex!). The search terms are highlighted in yellow in the output.

**Usage:**
```bash
script.php "search term 1" "search term 2" /path/to/file
```

# Features used
This recipie uses the following Macrame features:
* [arguments](../04_Manual/02_Handling_Arguments.md)
* [text](../04_Manual/03_Styled_Text_Output.md)
* [file](../04_Manual/06_File_Read_and_Write.md)

# Example
```PHP
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

$macrame = new Macrame("simple grep");

if($macrame->running()) {

    /**
     * Get the last positional argument in the list, which is the file to inspect
     */
    $file = $macrame->args('positional')->last();

    /**
     * Get all the other positional arguments before the last one, the terms to search
     */
    $searches = array_slice($macrame->args('positional')->all(), 0, -1);

    /**
     * Convert search terms to a preg-style regex
     */
    $searchesRegex = '/'.join('|', $searches).'/';

    /**
     * Get the patterns and replacemens so preg_replace() can highlight each search term
     */
    $patterns = array_map(fn($s) => "/$s/", $searches);
    $replacements = array_map(fn($s) => $macrame->text($s)->colour('yellow')->get(), $searches);

    /**
     * Iterate over each line in file using byLine()'s generator.
     * Test for the existence of any of the search terms. Replace the search term
     * with the ANSI-coloured search term using preg_replace(). Output using
     * text's write() method.
     */
    foreach($macrame->file($file)->byLine() as $line) {
        if(preg_match($searchesRegex, $line) > 0) {
            $macrame->text(preg_replace($patterns, $replacements, $line))->write();
        }
    }
}
```

# Walkthrough
The first step is to get the command line arguments. There are two types of arguments: the file to search on, which is always the last positional argument, and the arbitrary number of search terms, which come before the file.


Getting the path to the file is done using the `args` object's `last()` method.
```PHP
$file = $macrame->args('positional')->last();
```

To get the arbitrary number of search terms, the `all()` method for the `args` object is used to get all the arguments as an array. Then [`array_slice()`](https://www.php.net/manual/en/function.array-slice.php) is applied so that all the arguments except the last one are assigned to the `$searches` variable.
```PHP
$searches = array_slice($macrame->args('positional')->all(), 0, -1);
```

Since this script uses [`preg_match()`](https://www.php.net/manual/en/function.preg-match.php), the array of search terms in `$searches` needs to be converted into a regular expression string. This is done wiht 

```PHP
$searchesRegex = '/'.join('|', $searches).'/';
```

The result in `$searchesRegex` is a string that looks like `/term1|term2|term3`.

Highlighting of the search terms in the output is done with [`preg_replace()`](https://www.php.net/manual/en/function.preg-replace.php), which requires an array of patterns to match and a matching array of strings to replace each pattern with.

```PHP
$patterns = array_map(fn($s) => "/$s/", $searches);
$replacements = array_map(fn($s) => $macrame->text($s)->colour('yellow')->get(), $searches);
```

The array of patterns is an array containing each search term delimited as a regular expression. ie. if one of the search terms is `bicycle`, the pattern is `/bicycle/`.

The array of replacements for the patterns is the search term with ANSI colourization tags added to them to make them yellow in the terminal. This is done using the `colour()` method on `text()`. The Macrame text object is returned as a string using `get()` and assigned to the `$replacements` array.

Finally, the contents of the file is iterated over using the `byLine()` method from Macrame's `file` object. The `byLine()` method returns a generator that can be used in a `foreach()` loop just as an array would. Since the `byLine()` method does not read in the entire contents of the file, it can be used with files of very large sizes without worrying about system memory.

Inside the loop, `preg_match()` is applied to each line to determine if it contains any of the search terms listed in `$searchesRegex`.

If one or more terms is found, the search terms in the line are replaced with colourized versions using `preg_replace()` and the string is output to the console using the `write()` method of Macrame's `text` object.
```PHP
foreach($macrame->file($file)->byLine() as $line) {
    if(preg_match($searchesRegex, $line) > 0) {
        $macrame->text(preg_replace($patterns, $replacements, $line))->write();
    }
```
