Macrame provides a `text` object that allows building ANSI-styled and formatted text. 

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
