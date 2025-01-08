Macrame is a library that provides features that allow developers to create interactive, good-looking command-line scripts.

# Macrame objects

Macrame functionality is provided by objects, each of which focuses on a particular feature set. The objects are:

* **[MacrameArgs](04_Manual/02_Handling_Arguments.md)**: Command line argument parsing and reading.
* **[MacrameDownload](04_Manual/08_Downloading_files.md)**: Remote file downloading with dynamic progress display.
* **[MacrameFiglet](04_Manual/10_Headlines_with_Figlet.md)**: ASCII art headlines.
* **[MacrameFile](04_Manual/06_File_Read_and_Write.md)**: Safer file access.
* **[MacrameInput](04_Manual/04_Getting_User_Text_Input.md)**: User text input.
* **[MacrameIO](04_Manual/11_Input_Output.md)**: Low-level input/output functionality.
* **[MacrameMenu](04_Manual/05_Menus_and_Such.md)**: Interactive menus.
* **[MacrameSpinner](04_Manual/09_Spinners_and_Tasks.md)**: Animated spinner display for functions run in the background.
* **[MacrameTable](04_Manual/07_Table_Output.md)**: ASCII table output.
* **[MacrameText](04_Manual/03_Styled_Text_Output.md)**: Text styling and output.
* **[MacrameValidator](04_Manual/04_Getting_User_Text_Input.md#validating-readline-input)**: Input validation rules.

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            The <code>MacrameIO</code> object is designed for internal use by Macrame. It is recommended to use the higher-level functionality in, ie. <code>MacrameInput</code> or `MacrameText` instead.
        </div>
    </span>
</div>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            The <code>MacrameValidator</code> object is not designed to be used independently. See the documentation on <a href="Manual/Getting_User_Text_Input.html"><code>MacrameInput</code></a> on how to validate user input.
        </div>
    </span>
</div>

# Instantiating objects

Each object can be instantiated by calling a function in the `Macrame` object.

```PHP
// create a new Macrame object
$macrame = new Macrame();

$macrameArgs = $macrame->args("somearg");
$macrameDownload = $macrame->download("https://someurl.ca/somefile");
$macrameFiglet = $macrame->figlet("Some headline");
$macrameInput = $macrame->input();
$macrameMenu = $macrame->menu();
$macrameSpinner = $macrame->spinner();
$macrameTable = $macrame->table(['header 1', 'header 2'], [['data 1', 'data 2']]);
$macrameText = $macrame->text("Some text");

```

# Chaining method calls

Macrame calls are chainable. For instance, text can be output using both these methods.

```PHP
$macrame = new Macrame();

// chained call
$macrame->text("Some text")->write();

// no chaining
$macrameText = $macrame->text("Some text");
$macrameText->write();
```

# Script structure

Macrame has a recommended basic script structure. An explanation of this structure is given in the [Quick Start](02_Quick_Start.md) section.

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

    // user code here

    // exit cleanly
    $macrame->exit();
}
```


