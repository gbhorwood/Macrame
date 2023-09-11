Macrame provides several methods to read user text input, either as lines or single keystrokes.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#reading-a-line-of-user-input">Reading a line of user input</a><br>
<a href="#validating-readline-input">Validating readline input</a><br>
<a href="#validator-list">Validator list</a><br>
</div>

# Quickref
```PHP
$macrame = new Macrame();

// string. Read one line of user input with prompt
$line = $macrame->input()->readline('Enter a line: ');

// string. Read user input and validate it.
$line = $macrame->input()
                ->isLengthMin(4, 'Must be 4 more than characters')
                ->readline();


// string. Read a line of user input with a validator and echo '*' 
$password = $macrame->input()
                    ->isLengthMin(8, 'Must be at least eight characters.')
                    ->readPassword("Password: ");

// string. Read content piped in as a string
$pipedContent = $macrame->input()->readPipe();

// iterator. Read content piped in one line at a time
foreach($macrame->input()->readPipeByLine() as $line){
    print $line.PHP_EOL;
}

// string. Read a single keystroke
$key = $macrame->input()->readKey('Hit one key: ');
```

# Reading a line of user input
User text input is read using the method `readline()` from the class `MacrameInput`.

```PHP
$userInput = $macrame->input()->readline();
```

The `readline()` method waits for one line of user input, finished with the `<RETURN>` key, and returns it as a string.

An optional prompt can be passed to `readline()`

```PHP
$userEmail = $macrame->input()->readline('enter email: ');
```

# Validating readline input
An arbitrary number of validators can be applied to `readline()` input. When validators are applied, the user will be prompted for input until that input meets all the validation criteria.

Validators are added to the `MacrameInput` object. For example, to validate that the text entered by the user is a valid email address, the `isEmail()` validator can be applied:

```PHP
$userEmail = $macrame->input()
             ->isEmail()
             ->readline('enter email: ');
```

In this example, the user will be prompted to enter text until the input is a valid email.

An optional error message can be added to all validators:

```PHP
$userEmail = $macrame->input()
             ->isEmail('Must be a valid email address')
             ->readline('enter email: ');
```

If the input does not pass validation, the error message will be displayed and the user will be prompted to enter input again.

An arbitrary number of validators can be applied to input. All validators must pass for the input to be considered valid. In this example, the user input must be between five and nine characters long.

```PHP
$userString = $macrame->input()
              ->isLengthMin(5, 'Must be at least 5 characters')
              ->isLengthMax(9, 'Cannot be longer than 9 characters')
              ->readline('enter a string: ');
```

# Validator list

| Validator | Description |
| :-------- | :---------- |
| `isLengthMin(int $min, ?string $errorMessage)` | Minimum string length |
| `isLengthMax(int $min, ?string $errorMessage)` | Maximum string length |
| `isPregMatch(string $expression, ?string $errorMessage)` | Matches [`preg_match()`](https://www.php.net/manual/en/function.preg-match.php) expression |
| `isEqualTo(string $value, ?string $errorMessage)` | Equal to `$value` |
| `isOneOf(array $values, ?string $errorMessage)` | Equal to one of `$values` |
| `isEntropyMin(float $min, ?string $errorMessage)` | Has entropy of `$min` or more |
| `doesContain(string $substring, ?string $errorMessage)` | Contains substring `$substring` |
| `doesNotContain(string $substring, ?string $errorMessage)` | Does not contain substring `$substring` |
| `isInt(?string $errorMessage)` | Is an integer |
| `isNumber(?string $errorMessage)` | Is any number |
| `isEmail(?string $errorMessage)` | Is a valid email |
| `isUrl(?string $errorMessage)` | Is a valid url |
| `isIpAddress(?string $errorMessage)` | Is a valid ipv4 or ipv6 address |
| `isDate(?string $errorMessage)` | Is a valid date of any format |




