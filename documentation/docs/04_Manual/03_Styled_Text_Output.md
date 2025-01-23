<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Warning concerning emojis</b>
        </div>
        <div style='margin-left:1em;'>
            Due to changes in PHP 8.1's multibyte string extension, emojis may not be aligned properly
            in text output when using older versions of PHP.
        </div>
    </span>
</div>

Macrame provides a `text` object that allows building ANSI-styled and formatted text. 

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#creating-text">Creating text</a><br>
<a href="#getting-and-outputting-text">Getting and outputting text</a><br>
<a href="#changing-and-appending-text">Changing and appending text</a><br>
<a href="#applying-colour-to-text">Applying colour to text</a><br>
<a href="#applying-background-colour-to-text">Applying background colour to text</a><br>
<a href="#applying-styles-to-text">Applying styles colour to text</a><br>
<a href="#combining-coloured-and-styled-strings">Combining coloured and styled strings</a><br>
<a href="#using-tags-to-style-text">Using tags to style text</a><br>
<a href="#aligning-text">Aligning text</a><br>
<a href="#wraping-wide-text">Wrapping wide text</a><br>
<a href="#paging-long-text">Paging long text</a><br>
<a href="#notice-levels">Notice levels</a><br>
</div>


# Quickref
```PHP
$macrame = new Macrame();

// Write text straight to screen
$macrame->text('Hello world')->write();
$macrame->text('Hello world')->write(true);             // Add newline

// Write text to screen on STDERR
$macrame->text('Goodbye world')->writeError();

// changing text content
$myText = $macrame->text('Hello world');
$myText->text('Goodbye world')->write();

// appending text content
$myText = $macrame->text('Hello')->append(' world')->write();

// Get text as string
$txt = $macrame->text('Hello world')->get();

// Colourize text
$macrame->text('Hello world')->colour('red')->write();  // Red text
$macrame->text('Hello world')                           // Background red
        ->backgroundColour('red')
        ->write();
$macrame->text('Hello world')                           // White on red 
        ->colour('white')
        ->backgroundColour('red')
        ->write();

// Style text
$macrame->text('Hello world')->style('bold')->write();   // Bold text
$macrame->text('Hello world')->style('italic')->write(); // Italic text

// Style with tags
$macrame->text('<!BOLD!>bold<!CLOSE!> text')->write();
$macrame->text('<!RED!>red<!CLOSE!> text')->write();

// Align text in screen
$macrame->text('Hello world')->centre()->write();        // Centre
$macrame->text('Hello world')->right()->write();         // Right
$macrame->text('Hello world')->left()->write();          // Left (default)

// Wrap text to screen width
$macrame->text('Hello world')->wrap()->write();

// Output text paged to screen height
$macrame->text('Hello world')->page();

// Levels
$macrame->text('Success!')->ok();                        // ok
$macrame->text('Oops!')->error();                        // error
$macrame->text('Success!')->ok(true);                    // ok reverse green
```

# Creating text
Macrame provides the `text()` method to accept a string and return an object of `MacrameText`. The `MacrameText` object exposes methods to style, format and output the string.

```PHP
$macrame = new Macrame();

$myText = $macrame->text('Hello world');
```

All text methods are chainable. So, for instance, to create new text object, colour it red and write it to the screen, one could chain the methods:

```PHP
$macrame = new Macrame();

$macrame->text('Hello world')->colour('red')->write();
```

# Getting and outputting text
The text object has two output options:

* `get()`: Get the text as a string
* `write()`: Write the text directly to screen

```PHP
$macrame->text('Hello world)->write();                 // void. Write text to screen
$myTextString = $macrame->text('Hello world')->get();  // string. Get text as string

$myText = $macrame->text('Hello world');               // MacrameText. Get text object
$myText->write();
```

# Changing and appending text
The text in a text object can be changed or added to after creation.

Changing the text of a text object can be done by calling the `text()` method.
```PHP
// Set the text on creation
$myText = $macrame->text('Hello world');

$myText->write(); // Hello world

// Change the text
$myText->text('Something different');
$myText->write(); // Something different
```

Note that any colours or styles that are set are still applied to the new text.

Appending text to a text object can be done with `append()`. For instance, to append each element of an array to an existing MacrameText object:

```PHP
// an array of strings to add to a text object
$options = [
    'walk',
    'bike', 
    'bus',
];

// create the text object with initial text
$ouput = $macrame->text("Transportation options".PHP_EOL);

// append each element of the array to the text object
foreach($options as $option) {
    $ouput->append("  - ".$option.PHP_EOL);
}

$ouput->write();
```
The above code will output:

<tt>
Transportation options <br>
  - walk <br>
  - bike <br>
  - bus <br>
</tt>
<p />

# Applying colour to text
Macrame can set the foreground colour of text with the `colour()` method. The `colour()` method takes the name of the colour as its only argument.

```PHP
$macrame->text('Hello world')->colour('red')->write(); // Output text in red to screen
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>🇺🇸 Note</b>
        </div>
        <div style='margin-left:1em;'>
            <code>color()</code> is provided as an alias for <code>colour()</code>
        </div>
    </span>
</div>

Colours are rendered using ANSI escape codes, and will only work with ANSI-compliant terminals (which is, basically, all of them).

The complete list of colours available is:

| name | example | result |
| ---- | ----- | -- |
| black | `$macrame->text('Hello')->colour('black')->write()` | Hello |
| red | `$macrame->text('Hello')->colour('red')->write()` | <span style="color:red">Hello</span> |
| green | `$macrame->text('Hello')->colour('green')->write()` | <span style="color:green">Hello</span> |
| yellow | `$macrame->text('Hello')->colour('yellow')->write()` | <span style="color:yellow">Hello</span> |
| blue | `$macrame->text('Hello')->colour('blue')->write()` | <span style="color:blue">Hello</span> |
| magenta | `$macrame->text('Hello')->colour('magenta')->write()` | <span style="color:magenta">Hello</span> |
| cyan | `$macrame->text('Hello')->colour('cyan')->write()` | <span style="color:cyan">Hello</span> |
| white | `$macrame->text('Hello')->colour('white')->write()` | <span style="color:white">Hello</span> |

Text can only have one foreground colour. Therefore, the last colour applied to a text object will take precedence. For example:

```PHP
$macrame->text('Hello world')->colour('green')->colour('red')->write(); // red text
```

Will result in red text.

# Applying background colour to text
Macrame can set the background colour of text with the `backgroundColour()` method. The `backgroundColour()` method takes the name of the colour as its only argument.

```PHP
$macrame->text('Hello world')->backgroundColour('red')->write(); // Output text with background red to screen
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>🇺🇸 Note</b>
        </div>
        <div style='margin-left:1em;'>
            <code>backgroundColor()</code> is provided as an alias for <code>backgroundColour()</code>
        </div>
    </span>
</div>

Colours are rendered using ANSI escape codes, and will only work with ANSI-compliant terminals (which is, basically, all of them).

The complete list of background colours available is:

| name | example | result |
| ---- | ----- | -- |
| black | `$macrame->text('Hello')->backgroundColour('black')->write()` | <span style="background-color:black">Hello</span> |
| red | `$macrame->text('Hello')->backgroundColour('red')->write()` | <span style="background-color:red">Hello</span> |
| green | `$macrame->text('Hello')->backgroundColour('green')->write()` | <span style="background-color:green">Hello</span> |
| yellow | `$macrame->text('Hello')->backgroundColour('yellow')->write()` | <span style="background-color:yellow">Hello</span> |
| blue | `$macrame->text('Hello')->backgroundColour('blue')->write()` | <span style="background-color:blue">Hello</span> |
| magenta | `$macrame->text('Hello')->backgroundColour('magenta')->write()` | <span style="background-color:magenta">Hello</span> |
| cyan | `$macrame->text('Hello')->backgroundColour('cyan')->write()` | <span style="background-color:cyan">Hello</span> |
| white | `$macrame->text('Hello')->backgroundColour('white')->write()` | <span style="background-color:white">Hello</span> |

Text can only have one background colour. Therefore, the last background colour applied to a text object will take precedence. For example:

```PHP
$macrame->text('Hello world')->backgroundColour('green')->backgroundColour('red')->write(); // text on a red background
```

Will result in text on a red background.

Foreground and background colours can be combined.

```PHP
$macrame->text('Hello world')->colour('white')->backgroundColour('red')->write(); // white text on a red background
```

Results in white text on a red background.

# Applying styles to text
Styles, such as bold or italic, can by applied to Macrame text using the `style()` method.

```PHP
$macrame->text('Hello world')->style('bold')->write();
```

Multiple styles can be applied to text. This will style text as bold and italic:

```PHP
$macrame->text('Hello world')->style('bold', 'italic')->write();
```

As will this:

```PHP
$macrame->text('Hello world')->style('bold')->style('italic')->write();
```

The available styles are:

| name | example | result |
| ---- | ----- | -- |
| bold | `$macrame->text('Hello')->style('bold')->write()` | <b>Hello</b> |
| italic | `$macrame->text('Hello')->style('italic')->write()` | <i>Hello</i> |
| underline | `$macrame->text('Hello')->style('underline')->write()` | <u>Hello</u> |
| strikethrough | `$macrame->text('Hello')->style('strikethrough')->write()` | <strike>Hello</strike> |
| strike | `$macrame->text('Hello')->style('strike')->write()` | <strike>Hello</strike> |

# Combining coloured and styled strings
Colours and styles can be applied to text objects to create strings that can be combined in new text objects.

```PHP
$warning = $macrame->text('WARNING')
                   ->colour('white')
                   ->backgroundColour('red')
                   ->style('bold')
                   ->get();

$disclaimer = $macrame->text('you have been warned')
                      ->style('italic')
                      ->get();

$macrame->text("${warning}: Proceed at your own risk. ${disclaimer}")->write();
```

Will output:

<code><b style="color:white; background-color:red">WARNING</b>: Proceed at your own risk. <i>you have been warned</i></code>

# Using tags to style text
Macrame can style parts of a string using styling tags:

```PHP
$taggedString =<<<TXT
This text is <!RED!>red<!CLOSE!> and
this text is <!BOLD!>bold<!CLOSE!>.

$macrame->text($taggedString)->write();
TXT;
```

The above code will output:

<tt>
This text is <span style="color:red">red</span> and<br>
this text is <b>bold</b>.
</tt>

The available tags are:

| tag | description |
| ---- | ----- |
| <tt><!BLACK!></tt> | Black text |
| <tt><!RED!></tt> | Red text |
| <tt><!GREEN!></tt> | Green text |
| <tt><!YELLOW!></tt> | Yellow text |
| <tt><!BLUE!></tt> | Blue text |
| <tt><!MAGENTA!></tt> | Magenta text |
| <tt><!CYAN!></tt> | Cyan text |
| <tt><!WHITE!></tt> | White text |
| <tt><!BACKGROUND_BLACK!></tt> | Black background for text |
| <tt><!BACKGROUND_RED!></tt> | Red background for text |
| <tt><!BACKGROUND_GREEN!></tt> | Green background for text |
| <tt><!BACKGROUND_YELLOW!></tt> | Yellow background for text |
| <tt><!BACKGROUND_BLUE!></tt> | Blue background for text |
| <tt><!BACKGROUND_MAGENTA!></tt> | Magenta background for text |
| <tt><!BACKGROUND_CYAN!></tt> | Cyan background for text |
| <tt><!BACKGROUND_WHITE!></tt> | White background for text |
| <tt><!BOLD!></tt> | Bold style text |
| <tt><!ITALIC!></tt> | Italic style text |
| <tt><!UNDERLINE!></tt> | Underline style text |
| <tt><!STRIKE!></tt> | Strikethrough style text |
| <tt><!CLOSE!></tt> | Close <i>all</i> preceeding tags |

## The <tt><!CLOSE!></tt> tag behaviour
The `<!CLOSE!>` tag closes _all_ preceeding tags. Unlike HTML, there is no nested closing of tags. This is a feature of ANSI escape codes. 

In the following example, the 'GREEN' tag is overwritten by the 'RED' tag and then both are closed by the 'CLOSE' tag.

```PHP
$taggedString =<<<TXT
Text <!GREEN!>starts green and then <!RED!>turns red<!CLOSE!> and then has no colour<!CLOSE!>.
TXT;

$macrame->text($taggedString)->write();
```
The output of the above snippet will be:

<tt>
Text <span style="color:green">starts green and then </span><span style="color:red">turns red</span> and then has no colour.
</tt>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            Tags can also be written in all lowercase.<br>
            For instance, <code>&lt;!RED!&gt;</code> can be written as <code>&lt;!red!&gt;</code>
        </div>
    </span>
</div>

# Aligning text
Text can be aligned in the terminal either `left()`, `right()` or `centre()`. The default alignment is left. 

```PHP
$alignedText =<<<TEXT
Multi-line text
that can be aligned in 
the terminal.
TEXT;

$macrame->text($alignedText)->centre()->write();    // align centre
$macrame->text($alignedText)->right()->write();     // align right
$macrame->text($alignedText)->left()->write();      // align left (default)
```

Macrame tries to always leave a margin on the right-hand side of the console when doing alignment, so `right()` and `centre()` alignments will be shifted a little bit leftward.

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>🇺🇸 Note</b>
        </div>
        <div style='margin-left:1em;'>
            <code>center()</code> is provided as an alias for <code>centre()</code>
        </div>
    </span>
</div>

# Wrapping wide text
Text that is too wide for the terminal can be wrapped to the terminal width with the `wrap()` method.

```PHP
$wrappedText =<<<TEXT
A line or lines of text that are too wide for the 
current terminal.
TEXT;

$macrame->text($wrappedText)->wrap()->write();  // wrapped to terminal width
```

Macrame tries to always leave a margin on the right-hand side of the console when doing alignment, so `wrap()` will usually have some space on the right.

# Paging long text
Text that is too long to fit in the terminal screen can be paged using the `page()` method.

```PHP
$pagedText =<<<TEXT
Text that is too long
for the terminal height.
TEXT;

$macrame->text($pagedText)->wrap()->page();  // page on terminal height. note usage of wrap()
```

The `page()` method outputs a number of lines of text to fill the current terminal size and then prompts the user for the next page. The user can respond to the prompt in three ways:

| keys | result |
| ---- | ------ |
| `<SPACE>` | The next full page is output |
| `<RETURN>` | The next single line is output |
| `q` | Output is stopped and the script continues |

Note that for `page()` to work consistently, `wrap()` should be applied to the text. Paging is done on line endings and, without `wrap()` it is not guaranteed that lines will be shorter than the screen width.

# Notice levels
Macrame provides convenience methods for outputting notices for various levels such as 'ok' or 'error'.

```PHP
$macrame->text('that worked!')->ok();
```

Will write the following line to `STDOUT`.

<tt>
[<b style="color:green">OK</b>] that worked!<p />
</tt>

The colouration of the level can be reversed by passing `true` to notice level method. For instance, to reverse the OK colouration:

```PHP
$macrame->text('that worked!')->ok(true);
```

<tt>
<span style="color:white;background-color:green">[<b>OK</b>]</span> that worked!<p />
</tt>

Macrame has the following methods for notice level outputs:

| method | example | output |
| ------ | ------- | ------ |
| `ok()` | `$macrame->text('message')->ok()` | <tt>[<b style="color:green">OK</b>] message</tt> |
| `ok(true)` | `$macrame->text('message')->ok(true)` | <tt><span style="color:white;background-color:green">[<b>OK</b>]</span> message<tt> |
| `debug()` | `$macrame->text('message')->debug()` | <tt>[<b style="color:green">DEBUG</b>] message</tt> |
| `debug(true)` | `$macrame->text('message')->debug(true)` | <tt><span style="color:white;background-color:green">[<b>DEBUG</b>]</span> message<tt> |
| `info()` | `$macrame->text('message')->info()` | <tt>[<b style="color:green">INFO</b>] message</tt> |
| `info(true)` | `$macrame->text('message')->info(true)` | <tt><span style="color:white;background-color:green">[<b>INFO</b>]</span> message<tt> |
| `message()` | `$macrame->text('message')->message()` | <tt>[<b style="color:green">MESSAGE</b>] message</tt> |
| `message(true)` | `$macrame->text('message')->message(true)` | <tt><span style="color:white;background-color:green">[<b>MESSAGE</b>]</span> message<tt> |
| `warning()` | `$macrame->text('message')->warning()` | <tt>[<b style="color:#F6BE00;">WARNING</b>] message</tt> |
| `warning(true)` | `$macrame->text('message')->warning(true)` | <tt><span style="color:white;background-color:#F6BE00;">[<b>WARNING</b>]</span> message<tt> |
| `alert()` | `$macrame->text('message')->alert()` | <tt>[<b style="color:#F6BE00;">ALERT</b>] message</tt> |
| `alert(true)` | `$macrame->text('message')->alert(true)` | <tt><span style="color:white;background-color:#F6BE00;">[<b>ALERT</b>]</span> message<tt> |
| `error()` | `$macrame->text('message')->error()` | <tt>[<b style="color:red">ERROR</b>] message</tt> |
| `error(true)` | `$macrame->text('message')->error(true)` | <tt><span style="color:white;background-color:red">[<b>ERROR</b>]</span> message<tt> |
| `critical()` | `$macrame->text('message')->critical()` | <tt>[<b style="color:red">CRITICAL</b>] message</tt> |
| `critical(true)` | `$macrame->text('message')->critical(true)` | <tt><span style="color:white;background-color:red">[<b>CRITICAL</b>]</span> message<tt> |
| `emergency()` | `$macrame->text('message')->emergency()` | <tt>[<b style="color:red">EMERGENCY</b>] message</tt> |
| `emergency(true)` | `$macrame->text('message')->emergency(true)` | <tt><span style="color:white;background-color:red">[<b>EMERGENCY</b>]</span> message<tt> |




