Command line scripts often accept arguments; things like `--help` or `-vvv` or `/path/to/input`. Macrame provides a set of tools for parsing and accessing argument data.


<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#types-of-arguments">Types of arguments</a><br>
<a href="#parsing-arguments">Parsing arguments</a><br>
<a href="#testing-if-an-argument-was-passed">Testing if an argument was passed</a><br>
<a href="#getting-the-count-of-an-argument">Getting the count of an argument</a><br>
<a href="#getting-the-value-of-an-argument-assignment">Getting the value of an argument assignment</a><br>
<a href="#handling-positional-arguments">Handling positional arguments</a><br>
<a href="#full-example">Full example</a><br>
</div>

# Quickref
```PHP
$macrame = new Macrame();

$macrame->args('foo')->exists();    // bool. True if --foo or --foo=<val> was passed
$macrame->args('foo')->count();     // int. Number of times --foo was passed
$macrame->args('f')->count();       // int. Number of times -f was passed, either as -ff or -f -f
$macrame->args('foo')->first();     // string. The value of the first --foo=<val> argument 
$macrame->args('foo')->last();      // string. The value of the last --foo=<val> argument 
$macrame->args('foo')->all();       // array. All of the values of --foo=<val>

// positional arguments are not keyed, but identified by position starting at left
$macrame->args('positional')->all();    // array. All of the values of positional arguments
$macrame->args('positional')->count();  // int.
$macrame->args('positional')->first();  // string.
$macrame->args('positional')->last();   // string.
```

# Types of arguments
Macrame handles four different types of user arguments:

| Type | Examples | Description |
| :--- | :------- | :---------- |
| **Switches** | `script.php -h -vvv` | Single letter options identified by one dash. Can be combined. |
| **Long arguments** | `script.php --help` | Longer arguments. Identified by two dashes. |
| **Assignments** | `script.php --file=<path>` | Arguments that assign a value to a name. Identified by two dashes and an equals sign. |
| **Positional** | `script.php "some input"` | Arguments identified only by their positions |

# Parsing arguments
Arguments can be parsed from the command and accessed by passing the name of the argument to the `args()` method:

```PHP
$macrame->args('name');
```

The `args()` method returns an object of the `MacrameArgs` class containing data on that particular argument. The object has methods for determining the existence, number and value of the argument.

# Testing if an argument was passed
You can test if an argument was passed on the command line by using the `exists()` method.

```PHP
if ($macrame->args('foo')->exists()) {
    // argument '--foo' or '--foo=' was passed
}
```

The call to `exists()` will return true if the script is executed like:

```Bash
script.php --foo
```

or

```Bash
script.php --foo=somevalue
```

Long arguments, assignments and switches can all be tested with `exists()`.

# Getting the count of an argument
Oftentimes, an argument is supplied to the script more than once. For instance, to indicate a verbosity level of three for your script, you may wish it to be called:

```Bash
script.php -v -v -v
```

The number of times an argument is supplied can be found using the `count()` method:

```PHP
$macrame->args('name')->count();
```

The `count()` method can be used for long arguments, assignments and switches. For instance, a script called like this:

```Bash
script --input=Hello --input=World -vv -h -h
```

Will result in a count of 2 for 'input', 'v' and 'h'.

```PHP
$macrame->args('input')->count(); // 2
$macrame->args('v')->count();     // 2
$macrame->args('h')->count();     // 2
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.1em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            Switches can be grouped under one hyphen. For instance:
            <p />
            <p style="font-family:monospace; margin-left:1em">
            script -v -v -v
            </p>
            Is treated the same as:
            <p />
            <p style="font-family:monospace; margin-left:1em">
            script -vvv
            </p>
        </div>
    </span>
</div>

# Getting the value of an argument assignment
There are two methods for getting the values of assignment arguments: `first()` and `all()`.

## Getting the first value
The `first()` method returns the first valid value of an argument, if any.
```PHP
// script.php --foo=bar 
$macrame->args('foo')->first(); // bar
```

If the assignment argument is given to the script more than once, the left-most value will be returned as the first.
```PHP
// script.php --foo=bar --foo="baz quux"
$macrame->args('foo')->first(); // 'bar'
```

Only a valid, non-null value will be returned. Long arguments and null-assignments will be ignored.
```PHP
// script.php --foo= --foo --foo=bar
$macrame->args('foo')->first(); // 'bar'
```


## Getting the last value
The `last()` method returns the last valid value of an argument, if any.
```PHP
// script.php --foo=bar 
$macrame->args('foo')->last(); // bar
```

If the assignment argument is given to the script more than once, the right-most value will be returned as the last.
```PHP
// script.php --foo=bar --foo="baz quux"
$macrame->args('foo')->last(); // 'baz quux'
```

Only a valid, non-null value will be returned. Long arguments and null-assignments will be ignored.
```PHP
// script.php --foo= --foo=bar --foo
$macrame->args('foo')->first(); // 'bar'
```

## Getting all values
All the values assigned to an assignment argument can be returned as an array using the `all()` method.
```PHP
// script.php --foo=bar --foo="baz quux"
$macrame->args('foo')->all(); // ['bar', 'baz quux']
```

# Handling positional arguments
Positional arguments are arguments that do not have a predefined keyword, but who's role is determined by their position in arguments list. For instance, the gnu command [`cp`](https://man7.org/linux/man-pages/man1/cp.1.html) accepts a source and destination file path, ie `cp /path/to/source /path/to/destination`. The role of those arguments, source or destination, is determined by their position in the arguments list.

Macrame accesses those arguments using the key `positional`

```PHP
// script.php "first positional" "second positional"
$macrame->args('positional')->exists(); // true
$macrame->args('positional')->count();  // 2
$macrame->args('positional')->first();  // 'first positional'
$macrame->args('positional')->all();    // ['first positional', 'second positional']
```

# Full example
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

**The script**
```PHP
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

$macrame = new Macrame();

if($macrame->args('foo')->exists()) {
    $count = $macrame->args('foo')->count();
    $macrame->text("The argument foo was passed $count times")->write(true);

    $firstValue = $macrame->args('foo')->first();
    $macrame->text("The first value passed to foo was '$firstValue'")->write(true);

    $allValues = $macrame->args('foo')->all();
    $macrame->text("All the values of foo are".print_r($allValues, true))->write(true);
}

if($macrame->args('positional')->exists()) {
    $count = $macrame->args('positional')->count();
    $macrame->text("There are $count positional arguments")->write(true);

    $firstPositional = $macrame->args('positional')->first();
    $macrame->text("The first positional argument passed was '$firstPositional'")->write(true);

    $allPositional = $macrame->args('positional')->all();
    $macrame->text("All the positional arguments are".print_r($allPositional, true))->write(true);
}
```

**Calling the script**
```Bash
script.php --foo=bar --foo="baz quux" "first positional" "second positional"
```

**The output**
```
The argument foo was passed 2 times
The first value passed to foo was 'bar'
All the values of foo areArray
(
    [0] => bar
    [1] => baz quux
)

There are 2 positional arguments
The first positional argument passed was 'first positional'
All the positional arguments are
Array
(
    [0] => first positional
    [1] => second positional
)
```
