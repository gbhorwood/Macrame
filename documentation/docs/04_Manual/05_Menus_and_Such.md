Macrame allows the creation of interactive menus that the user navigates with arrow and tab keys and selects with return. Menus can have a standard, vertical layout or a more compact horizontal layout. There is also a dynamic datepicker.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#creating-an-interactive-menu">Creating an interactive menu</a><br>
<a href="#creating-a-horizontal-menu">Creating a horizontal menu</a><br>
<a href="#creating-a-datepicker">Creating a datepicker</a><br>
<a href="#styling-menu-options">Styling menu options</a><br>
<a href="#aligning-menus">Aligning menus</a><br>
<a href="#erasing-menus">Erasing menus</a><br>
</div>

# Quickref
```PHP
$macrame = new Macrame();

$header = "Choose a Linux distro";

$options = [
    'Yggdrasil',
    'Hannah Montana Linux',
    'Elfstone',
];

// interactive vertical menu
$selected = $macrame->menu()->interactive($options, $header);

// interactive horizontal menu
$selected = $macrame->menu()->horizontal($options, $header);

// interactive date picker
$selected = $macrame->menu()->datePicker('1990-04-19', 'select a date');

// style and colour highlighted option
$macrame->menu()->styleSelected('bold');  // style of highlighted option
$macrame->menu()->colourSelected('red');  // colour of highlighted option
$macrame->menu()->colorSelected('red');   // synonym for colourSelected()

// style and colour non-highlighted options
$macrame->menu()->styleOption('italic');  // style of non-highlighted option
$macrame->menu()->colourOption('blue');   // colour of non-highlighted option
$macrame->menu()->colorOption('blue');    // synonym for colourOption()

// align items inside the menu
$macrame->menu()->optionLeft();
$macrame->menu()->optionRight();
$macrame->menu()->optionCentre();
$macrame->menu()->optionCenter();

// align the menu inside the terminal
$macrame->menu()->menuLeft();
$macrame->menu()->menuRight();
$macrame->menu()->menuCentre();
$macrame->menu()->menuCenter();

// erase menu after selection
$macrame->menu()->erase();
```

# Creating an interactive menu
Vertical, interactive menus can be created and displayed using `MacrameMenu`'s `interactive()` method.

The `interactive()` method accepts an array of strings as a list of options to display, and an optional header.

```PHP
$macrame = new Macrame();

$header = "Choose a Linux distro";

$options = [
    'Yggdrasil',
    'Hannah Montana Linux',
    'Elfstone',
];

// display menu of options. user selection is returned.
$selected = $macrame->menu()->interactive($options, $header);
```

Interactive menus are navigated by the user using the up and down arrow keys to highlight an option, and the `<RETURN>` key to select it. The `<TAB>` key functions as a down arrow key. Additionally, pressing any other key acts as a 'leader' key, highlighting the first option that starts with that character.

The selected option is returned by `interactive()` as a string.

## Headers for interactive menus
Headers can be any text. If the header text is styled with `MacrameText`'s style and colour methods, this styling will be displayed. If the header contains line breaks, ie. from php's `PHP_EOL`, they will be preserved.

```PHP
// this will be displayed with styling and line breaks
$header = 'choose <!ITALIC!>your<!CLOSE!> favourite'.
    PHP_EOL.
    $macrame->text("distribution")->colour("green")->get();
```

## Options for interactive menus
Options can be any text and can include line breaks, ie. from php's `PHP_EOL`.

All styling applied to options is removed before display. To apply styling to options see 'Styling menu options' below.

# Creating a horizontal menu
Horizontal, interactive menus can be created and displayed using `MacrameMenu`'s `horizontal()` method.

The `horizontal()` method accepts an array of strings as a list of options to display, and an optional header.

```PHP
$macrame = new Macrame();

$header = "Choose a Linux distro";

$options = [
    'Yggdrasil',
    'Hannah Montana Linux',
    'Elfstone',
];

// display menu of options. user selection is returned.
$selected = $macrame->menu()->horizontal($options, $header);
```

## Headers for horizontal menus
Headers can be any text. If the header text is styled with `MacrameText`'s style and colour methods, this styling will be displayed. If the header contains line breaks, ie. from php's `PHP_EOL`, they will be preserved.

```PHP
// this will be displayed with styling and line breaks
$header = 'choose <!ITALIC!>your<!CLOSE!> favourite'.
    PHP_EOL.
    $macrame->text("distribution")->colour("green")->get();
```

## Options for horizontal menus
<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Warning</b>
        </div>
        <div style='margin-left:1em;'>
            Options for horizontal menus should not contain line breaks.
        </div>
    </span>
</div>

Options can be any text but should _not_ contain any line breaks. 

All styling applied to options is removed before display. To apply styling to options see 'Styling menu options' below.

# Creating a datepicker
A datepicker menu can be created and displayed using `MacrameMenu`'s `datePicker()` method.

The `datePicker()` method accepts a starting date as a string, and an optional header.

```PHP
// interactive date picker
$selected = $macrame->menu()->datePicker('1990-04-19', 'select a date');
```

The starting date argument can be in any format that php's [DateTime](https://www.php.net/manual/en/class.datetime.php) can parse.

Datepickers are navigated by using the left and right arrows to select the year, month or day columns. The `<TAB>` key functions as a right-arrow key. The year, month and day values can be incremented or decremented using the down and up arrow keys. Additionally, literal values can by typed using leader keys, ie. in the month column, typing 'a' will select 'Apr'. Typing 'au' will select 'Aug'.

# Styling menu options
The default behaviour of `MacrameMenu` is for options to be displayed in plain text, with the currently selected option being highlighted in reverse. This can be changed using styling methods.

Unselected options can be given a style and colour using:

| Method | Description | Argument enumeration |
| :-------- | :---------- | :---- |
| `colourOption($colour)` | Apply colour to unselected option | black, red, green, yellow, blue, magenta, cyan, white |
| `colorOption($colour)` | Synonym for `colourOption` | black, red, green, yellow, blue, magenta, cyan, white |
| `styleOption($style)` | Apply style to unselected option | bold, italic, underline, strikethrough, strike |

Selected, highlighted options can be give a style and colour using:

| Method | Description | Argument enumeration |
| :-------- | :---------- | :---- |
| `colourSelected($colour)` | Apply colour to selected option | black, red, green, yellow, blue, magenta, cyan, white |
| `colorSelected($colour)` | Synonym for `colourSelected` | black, red, green, yellow, blue, magenta, cyan, white |
| `styleSelected($style)` | Apply style to selected option | bold, italic, underline, strikethrough, strike |

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            Any styling added to an option string using `MacrameText` will be erased
            and superceded by the colour and style directives used by MacrameMenu.
        </div>
    </span>
</div>


# Aligning menus
Macrame allows for options and headers to be aligned within a menu, and menus themselves to be aligned in the terminal, by using the alignment methods:

| Method | Description |
| :---- | :---- |
| `optionLeft()` | Options and headers to the left in the menu |
| `optionRight()` | Options and headers to the right in the menu |
| `optionCentre()` | Options and headers to the centre in the menu |
| `optionCenter()` | Synonym for `optionCentre()` |
| `menuLeft()` | Menu to the left in the terminal |
| `menuRight()` | Menu to the right in the terminal  |
| `menuCentre()` | Menu to the centre in the terminal  |
| `menuCenter()` | Synonym for `menuCentre()` |

For example, to centre align a menu's options and align the menu in the center of the terminal:

```PHP
$macrame->menu()->optionCentre()->menuCentre()->interactive($options, $header);
```

<div style='background-color:#EFF5F1; border-left: solid #CC5500 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
    <span>
        <div style='color:#cc5500; padding-bottom:0.3em; padding-top:0.3em'>
            <b>Note</b>
        </div>
        <div style='margin-left:1em;'>
            Macrame leaves a margin on the right of the terminal to improve readability.
        </div>
    </span>
</div>

# Erasing menus
All menus can be set to erase after the user has made a selection by calling the `erase()` method.

```PHP
$selected = $macrame->menu()->erase()->interactive($options, $header);
```



