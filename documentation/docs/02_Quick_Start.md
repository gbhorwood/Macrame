Once Macrame has been [installed via composer](01_Installation.md), a basic script can be scaffolded with a minimal amount of boilerplate. 

# Hello world
The canonical 'hello world' example in Macrame looks like this:

```PHP
#!/usr/bin/env php 
<?php
require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

// instantiate a Macrame object with the script name
$macrame = new Macrame("My Script Name");

// only execute if run from the command line
if($macrame->running()) {

    // confirm host is good. die on failure.
    $macrame->preflight();

    // output text to STDOUT
    $macrame->text("hello world")->write();

    // exit cleanly
    $macrame->exit();
}
```

# Hello world walkthrough

## The shebang
```PHP
#!/usr/bin/env php 
```
Tells the operating system which interpreter to use to run this script. Allows the script to be run without calling `php` on the command line. Must be the first line.

## Instantiating Macrame
```PHP
$macrame = new Macrame("My Script Name");
```
Creates the Macrame object. The argument is the name of the script as it appears in the output of `ps`.

## Only execute if run from the command line
```PHP
if($macrame->running())
```
Code inside this `if` block will only be run if the script is run on the command line.

## Preflight the host system
```PHP
$macrame->preflight();
```
Test to make sure php on the host system is capable of using Macrame. The requirements are:

* Minimum version 7.4
* `posix` extension loaded
* `mbstring` extension loaded

## The script body
```PHP
$macrame->text("hello world")->write();
```
The custom code of the script.
            
## Exiting cleanly
```PHP
$macrame->exit();
```
Cleans up any temporary files created in the script, resets the cursor to visible if necessary and returns a success code of 0.

# Set permissions and run

```BASH
chmod 755 /path/to/macrame/script.php
```

```BASH
/path/to/macrame/script.php
```


