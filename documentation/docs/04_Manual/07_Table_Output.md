Macrame allows for the creation of ascii-style tables, similar to the output from the mysql client, from data arrays.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#creating-a-table">Creating a table</a><br>
<a href="#output">Output</a><br>
<a href="#validating-input">Validating input</a><br>
<a href="#aligning-columns">Aligning columns</a><br>
<a href="#applying-styles">Applying styles</a><br>
</div>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Warning concerning emojis</b>
        </div>
        <div style='margin-left:1em;'>
            Due to changes in PHP 8.1's multibyte string extension, emojis may not be aligned properly
            in table output when using older versions of PHP.
        </div>
    </span>
</div>

# Quickref
```PHP
$headers = [
    'Artist',
    'Album',
];

$data = [
    ['The Velvet Underground', 'And Nico' ],
    ['Monk, Thelonious', 'Misterioso' ],
];

$macrame = new Macrame();

$macrame->table($headers, $data)->write();              // void. Output table to screen
$table = $macrame->table($headers, $data)->get();       // string. Get table as string

// column indexes start at 0
$macrame->table($headers, $data)->centre(1)->write();  // void. Centre align column 1
$macrame->table($headers, $data)->center(1)->write();  // void. Alias of centre()
$macrame->table($headers, $data)->right(1)->write();    // void. Right align column 1
$macrame->table($headers, $data)->left(0)->write();      // void. Left align column 1
```

# Creating a table
Tables are created as an object of class `MacrameTable`.

The easiest way to create a table object is with the `table()` method. The `table()` method takes two arguments:

* `$headers`: An array of strings representing the column headers
* `$data`: An array of arrays of strings representing the column data

```PHP
$headers = [...];
$data = [...];

$myTable = $macrame->table($headers, $data);
```

## Output
The table object has two output options:

* `get()`: Get the table as a string
* `write()`: Write the table directly to screen

```PHP
$headers = [...];
$data = [...];

$macrame->table($headers, $data)->write();                 // void. Write table to screen
$myTableString = $macrame->table($headers, $data)->get();  // string. Get table as string

$myTable = $macrame->table($headers, $data);               // MacrameTable. Get table object
$myTable->write();
```

## Validating input
Macrame tables demand that the number of columns in the `$headers` must match the number of columns in each of the arrays in `$data`. 

In the event of a column mismatch, Macrame will output a warning to screen and will return null in place of the table.

```PHP
$headers = [
    'Artist',
    'Album',
];

$data = [
    ['The Velvet Underground', 'And Nico', '1967' ], // too many columns
    ['Monk, Thelonious'],                            // too few columns
];

// outputs a warning
$myTableString = $macrame->table($headers, $data)->get();

is_null($myTableString); // true
```

# Aligning columns
Columns can be aligned by passing the numerical column index to one of the `left()`, `right()` or `centre()` alignment methods. Columns are indexed starting at zero.

```PHP
$headers = [
    'Artist',
    'Album',
];

$data = [
    ['The Velvet Underground', 'And Nico' ],
    ['Monk, Thelonious', 'Misterioso' ],
];

// Align centre the 'Artist' column
$macrame->table($headers, $data)->centre(0)->write();
$macrame->table($headers, $data)->center(0)->write(); // American spelling works, too.

// Align right the 'Album' column
$macrame->table($headers, $data)->right(1)->write();

// Align left 'Artist' and align right 'Album'
$macrame->table($headers, $data)->left(0)->right(1)->write();

// Alternate method
$myTable = $macrame->table($headers, $data);
$myTable->right(0);
$myTable->center(1);
$myTable->write();
```
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

The default alignment is left.

If multiple different alignments are applied to a column, the last alignment applied will be used

## Alignment example
**Script**
```PHP
$headers = [
    'Artist',
    'Album',
];

$data = [
    ['The Velvet Underground', 'And Nico' ],
    ['Monk, Thelonious', 'Misterioso' ],
];

// Align centre 'Artist' and align right 'Album'
$macrame->table($headers, $data)->centre(0)->right(1)->write();
```
**Output**
```None
+------------------------+------------+
|         Artist         |      Album | 
+------------------------+------------+
| The Velvet Underground |   And Nico | 
|    Monk, Thelonious    | Misterioso | 
+------------------------+------------+
```

# Applying styles
<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            The example here uses Macrames <code>text()</code> feature for styling and outputting text.
        </div>
    </span>
</div>
Styles can be applied to tables by using strings created by MacrameText.

```PHP
$headers = [
    $macrame->text('Artist')->style('bold')->get(), // bold text
    $macrame->text('Album')->color('red')->get(),   // red text
];

$data = [
    ['The Velvet Underground', 'And Nico' ],
    ['Monk, Thelonious', 'Misterioso' ],
];

$macrame->table($headers, $data)->write();
```
The output of the above code will look like:

<tt>
+------------------------+------------+<br />
| <b>Artist</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| <span style="color:red">Album</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<br />
+------------------------+------------+<br />
| The Velvet Underground | And Nico&nbsp;&nbsp;&nbsp;|<br /> 
| Monk, Thelonious&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| Misterioso |<br /> 
+------------------------+------------+<br />
</tt>

Styles can be applied to strings used in both the `$headers` and `$data` arrays.
