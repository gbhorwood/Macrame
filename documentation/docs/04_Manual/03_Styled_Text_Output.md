Macrame provides a `text` object that allows building ANSI-styled and formatted text. 

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em'>
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

# Quickref
```PHP
$macrame = new Macrame();

// Write text straight to screen
$macrame->text('Hello world')->write();
$macrame->text('Hello world')->write(true);             // Add newline

// Write text to screen on STDERR
$macrame->text('Goodbye world')->writeError();

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

# Applying colour to text
Macrame can set the foreground colour of text with the `colour()` method. The `colour()` method takes the name of the colour as its only argument.

```PHP
$macrame->text('Hello world')->colour('red')->write(); // Output text in red to screen
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em'>
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

# Applying background colours to text
Macrame can set the background colour of text with the `backgroundColour()` method. The `backgroundColour()` method takes the name of the colour as its only argument.

```PHP
$macrame->text('Hello world')->backgroundColour('red')->write(); // Output text with background red to screen
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em'>
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

# Aligning text

# Wrapping wide text

# Paging long text

# Notice levels
