This recipe covers polling the user for an email and password for registration. Users will be prompted to input their email, password and their password again. Each input will be validated.

# Features used
This recipie uses the following Macrame features:
* [text input](../04_Manual/04_Getting_User_Text_Input.md)

# Example
```PHP
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

$macrame = new Macrame("register an account");

if($macrame->running()) {

    /**
     * Read and validate email address
     */
    $email = $macrame->input()
                     ->isEmail('Enter a valid email')
                     ->readLine('Email: ');

    /**
     * Read and validate password
     */
    $password = $macrame->input()
                        ->isLengthMin(8, 'Must be at least eight characters.')
                        ->doesNotContain(explode('@', $email)[0], 'Must not contain your name')
                        ->isPregMatch('/[0-9]+/', 'Must contain at least one number')
                        ->isPregMatch('/[A-Z]+/', 'Must contain at least one uppercase letter')
                        ->isPregMatch('/[a-z]+/', 'Must contain at least one lowercase letter')
                        ->isPregMatch('/[^\w]/', 'Must contain at least one special character')
                        ->isEntropyMin(3.0, 'Password not complex enough')
                        ->readPassword('Password: ');

    /**
     * Read password again and confirm it matches first one
     */
    $macrame->input()
            ->isEqualTo($password, 'Passwords must match.')
            ->readPassword("Repeat password: ");
}
```

# Walkthrough
This script prompts the user for three pieces of information: email, password, and the password again.

The first line uses the input objects `readLine()` function to read in the user's email address and return it to the `$email` variable. An optional prompt of 'Email:' is passed to `readLine()`. The validator `isEmail()` is applied to the chain.

Validators, such as `isEmail()`, make it so the user is prompted for input until that input passes _all_ the validators that have been applied.

The second line polls the user for their password using `readPassword()` and returns the entered value to the `$password` variable. The `readPassword()` method functions exactly the same as `readLine()` except that asterisks are echoed to the screen instead of the typed characters. An optional prompt is passed to `readPassword()` here.

There are several validators that are passed to `readPassword()`. Each validator must pass before the user's input is accepted. Each validator in this line takes two arguments: a value to test the input against (ie '8' as the minimum string length for `isLengthMin()`) and an optional error message to display if validation fails. If an error message is not passed, not output will be done if the validation fails and the user will be prompted to try again.

Since all validators must pass before the user's input is accepted, this chain of validators enforces that the password be at least eight characters long, contain an upper and lowercase letter, a number and special character, not contain the name portion of the email address and have an entropy of 3.0 or higher.

**Note:** The `isEntropyMin()` function can be used as an addition to other validators but should not be relied on to guage password strength. The password `secret1234`, for instance, has an entropy of 3.1.

The final line reads in the user's password again and tests that it is the same as password previously entered by using the `isEqualTo()` validator. The return value is not stored since it is already known.
