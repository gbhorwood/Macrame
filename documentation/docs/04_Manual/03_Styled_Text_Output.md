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

# Getting and outputting text

# Applying colour to text

# Applying styles to text

# Using tags to style text

# Aligning text

# Wrapping wide text

# Paging long text

# Notice levels
