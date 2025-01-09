Macrame enables running a time intensive function in the background while the script displays an animated 'working' spinner. There are several spinner styles to choose from and all spinner text is customizable using the `MacrameText` methods.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#creating-a-spinner">Creating a spinner</a><br>
<a href="#running-a-function-with-a-spinner">Running a function with a spinner</a><br>
<a href="#choosing-a-spinner-animation">Choosing a spinner animation</a><br>
<a href="#adding-custom-text-to-the-spinner">Adding custom text to the spinner</a><br>
<a href="#styling-the-spinner">Styling the spinner</a><br>
<a href="#setting-the-animation-speed">Setting the animation speed</a><br>
</div>

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Warning for Windows users</b>
        </div>
        <div style='margin-left:1em;'>
            Macrame backgrounds function execution by forking a child process using PHP's <a href="https://www.php.net/manual/en/function.pcntl-fork.php"><code>pcntl_fork()</code></a>. This may 
            or may not work (and has not been tested) on Windows. 
        </div>
    </span>
</div>

# Quickref
```PHP
$macrame = new Macrame();

// an example function to run with a spinner
$wait = function($seconds) {
    sleep($seconds);
    return "a value";
};

$result = $macrame->spinner()->run($wait, [1]); // mixed. default spinner. returns 'a value'

$spinner = $macrame->spinner();
$result = $spinner->run($wait, [1]); // mixed. default spinner. returns 'a value'

$result = $macrame->spinner->prompt("enter text: ")->run($wait, [1]) // mixed. adds prompt to spinner

$result = $macrame->spinner("star")->run($wait, [1])                   // mixed. use a different spinner animation
$result = $macrame->spinner->colour("red")->run($wait, [1])            // mixed. sets colour red
$result = $macrame->spinner->color("red")->run($wait, [1])             // mixed. synonym for colour
$result = $macrame->spinner->backgroundColour("red")->run($wait, [1])  // mixed. sets background colour red
$result = $macrame->spinner->backgroundColor("red")->run($wait, [1])   // mixed. synonym for backgroundColour
$result = $macrame->spinner->style("bold")->run($wait, [1])            // mixed. sets style bold
$result = $macrame->spinner->speed("fast")->run($wait, [1])            // mixed. sets animation speed

```

# Creating a spinner
Spinners are created and run using the `MacrameSpinner` class returned from Macrame's `spinner()` method.

```PHP
$spinner = $macrame->spinner();
```

The `spinner()` method accepts the name of the custom spinner as an optional argument. If no argument is provided, the default spinner is used.

# Running a function with a spinner
The purpose of the spinner feature is to allow script writers to run a function 'in the background' while displaying an animated spinner to the user.

Setting and running a function with a spinner is done with the `run()` method. 

```PHP
$result = $macrame->spinner()->run($function, $argsArray);
```

The `run()` method accepts two arguments:

* The function to run as `callable`
* An optional array of arguments to pass to the function

The return value of `run()` is the return value of the passed function.

```PHP
$macrame = new Macrame();

$wait = function($seconds) {
    sleep($seconds);
    return "a value";
};

$result = $macrame->spinner()->run($wait, [1]); // "a value"
```

The above example will display the default animated spinner to the user for one second and return the value "a value" to the variable `$result`.

To use the method of an object as the callable argument to `run()`, pass it as a two-element array of the object and a string of the method name:

```PHP
class someClass {
    public function wait(Int $seconds):String
    {   
        sleep($seconds);
        return "a value";
    }   
}

$someObject = new someClass();

$result = $macrame->spinner()->run([$someObject, 'wait'], [1]);
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            If the callable passed to <code>run()</code> does any output to screen, ie. with <code>print()</code> or <code>echo()</code> or similar, the spinner
            animation will run as normal, but output may not be as desired.
        </div>
    </span>
</div>

# Choosing a spinner animation
Macrame offers a number of different spinner animations. To use a spinner animation, pass its name as a string argument to the `spinner()` method:

```PHP
$macrame->spinner('star'); // use the 'star' spinner animation
```

The available spinner animations are:

| Animation | Characters |
| :-------- | :---------- |
| `standard` | `\|` `/` `-` `\` `\|` `/` `-`|
| `dots 1` |  `⠄` `⠆` `⠇` `⠋` `⠙` `⠸` `⠰` `⠠` `⠰` `⠸` `⠙` `⠋` `⠇` `⠆` |
| `dots 2` |  `⠁` `⠉` `⠙` `⠚` `⠒` `⠂` `⠂` `⠒` `⠲` `⠴` `⠤` `⠄` `⠄` `⠤` `⠴` `⠲` `⠒` `⠂` `⠂` `⠒` `⠚` `⠙` `⠉` `⠁` |
| `dots 3` |  `⠋` `⠙` `⠚` `⠒` `⠂` `⠂` `⠒` `⠲` `⠴` `⠦` `⠖` `⠒` `⠐` `⠐` `⠒` `⠓` `⠋` |
| `dots 4` |  `⠈` `⠉` `⠋` `⠓` `⠒` `⠐` `⠐` `⠒` `⠖` `⠦` `⠤` `⠠` `⠠` `⠤` `⠦` `⠖` `⠒` `⠐` `⠐` `⠒` `⠓` `⠋` `⠉` `⠈` |
| `dots 5` |  `⠁` `⠁` `⠉` `⠙` `⠚` `⠒` `⠂` `⠂` `⠒` `⠲` `⠴` `⠤` `⠄` `⠄` `⠤` `⠠` `⠠` `⠤` `⠦` `⠖` `⠒` `⠐` `⠐` `⠒` `⠓` `⠋` `⠉` `⠈` `⠈` |
| `cycle 1` |  `⠁` `⠂` `⠄` `⡀` `⢀` `⠠` `⠐` `⠈` |
| `cycle 2` |  `⣼` `⣹` `⢻` `⠿` `⡟` `⣏` `⣧` `⣶` |
| `cycle 3` |  `⢄` `⢂` `⢁` `⡁` `⡈` `⡐` `⡠` |
| `cycle 4` |  `⢹` `⢺` `⢼` `⣸` `⣇` `⡧` `⡗` `⡏` |
| `cycle 5` |  `⣾` `⣽` `⣻` `⢿` `⡿` `⣟` `⣯` `⣷` |
| `cycle 6` |  `⠋` `⠙` `⠹` `⠸` `⠼` `⠴` `⠦` `⠧` `⠇` `⠏` |
| `star` |  `✶` `✸` `✹` `✺` `✹` `✷` |
| `grow` |  `▁` `▃` `▄` `▅` `▆` `▇` `▆` `▅` `▄` `▃` |
| `stretch` |  `▏` `▎` `▍` `▌` `▋` `▊` `▉` `▊` `▋` `▌` `▍` `▎` |
| `corners 1` |  `▌` `▀` `▐` `▄` |
| `corners 2` |  `◢` `◣` `◤` `◥` |
| `pipe` |  `┤` `┘` `┴` `└` `├` `┌` `┬` `┐` |
| `balloon` |  ` ` `.` `o` `O` `@` `*` ` ` |
| `bounce 1` |  `⠁` `⠂` `⠄` `⠂` |
| `bounce 2` |  `.` `o` `O` `°` `O` `o` `.` |
| `bounce 3` |  `☱` `☲` `☴` |
| `rolling square` |  `◰` `◳` `◲` `◱` |
| `rolling circle 1` |  `◴` `◷` `◶` `◵` |
| `rolling circle 2` |  `◐` `◓` `◑` `◒` |
| `pulse 1` |  `⊶` `⊷` |
| `pulse 2` |  `▫` `▪` |
| `pulse 3` |  `□` `■` |
| `pulse 4` |  `▮` `▯` |
| `pulse 5` |  `◍` `◌` |
| `pulse 6` |  `◉` `◎` |
| `pulse 7` |  `⧇` `⧆` |
| `pulse 8` |  `☗` `☖` |
| `pulse 9` |  `ဝ` `၀` |
| `pulse 10` |  `◡` `⊙` `◠` |
| `pulse 11` |  `▓` `▒` `░` |
| `arrow` |  `←` `↖` `↑` `↗` `→` `↘` `↓` `↙` |
| `arrow emoji` |  `⬆️ ` `↗️ ` `➡️ ` `↘️ ` `⬇️ ` `↙️ ` `⬅️ ` `↖️ ` |
| `heart emoji` |  `💛 ` `💙 ` `💜 ` `💚 ` `❤️ ` |
| `clock emoji` |  `🕛 ` `🕐 ` `🕑 ` `🕒 ` `🕓 ` `🕔 ` `🕕 ` `🕖 ` `🕗 ` `🕘 ` `🕙 ` `🕚 ` |
| `earth emoji` |  `🌍 ` `🌎 ` `🌏 ` |
| `moon emoji` |  `🌑 ` `🌒 ` `🌓 ` `🌔 ` `🌕 ` `🌖 ` `🌗 ` `🌘 ` |

# Adding custom text to the spinner
A custom string can be prepended to the spinner animation by using the `prompt()` method.

```PHP
$macrame->spinner()->prompt("please wait ");
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Warning</b>
        </div>
        <div style='margin-left:1em;'>
            The prompt string must be one line. Prompt strings with line breaks in them will affect the spinner animation.
        </div>
    </span>
</div>

# Styling the spinner
The spinner animation and the optional prompt can be styled using the styling methods from `MacrameText`.

| Method | Description |
| :----- | :---------- |
| `colour()` | Set text colour, ie. 'red' |
| `color()` | Synonym for `colour` |
| `backgroundColour()` | Set text background colour, ie. 'white' |
| `backgroundColor()` | Synonym for `backgroundColour` |
| `style()` | Set text style, ie. 'bold' |

For example, to have a bold, red spinner on a white background

```PHP
$macrame->spinner()->colour('red')->style('bold')->backgroundColour('white');
```

For more details on styling, refer to the [MacrameText documentation page](03_Styled_Text_Output.md).

# Setting the animation speed
The speed of the animation can be set using the `speed()` method.

The `speed()` method accepts as an argument one of:

* `'slow'`
* `'medium'`
* `'fast'`
* `'very fast'`

```PHP
$macrame->spinner()->speed('fast')
```

the default speed is 'slow'.

