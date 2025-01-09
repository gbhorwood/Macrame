# Macrame
Macrame is a library for building interactive command line scripts in PHP. It provides methods to create interactive menus, format text for the terminal, handle command-line arguments, read and validate user input, run processes in the background, and more. Macrame was developed as an internal project for Fruitbat Studios/Cloverhitch Technologies/Kludgetastic Implementations. 

## Install
Macrame is installed via composer:

```shell
composer require gbhorwood/macrame
```

## Documentation and Resources
Full documentation and a set of examples are found on the [documentation site](https://macrame.fruitbat.studio), including:

* [Quick Start](https://macrame.fruitbat.studio/Quick_Start.html)
* [Crash Course](https://macrame.fruitbat.studio/Overview.html)
* [Full Manual](https://macrame.fruitbat.studio/Manual/Getting_Started.html)
* [Examples](https://macrame.fruitbat.studio/Cookbook/Intro.html)

## Hello world
The fastest way to get up and running is to use the 'hello world' example and replace the 'output text' line with your custom code.

```php
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

A full walkthrough of 'hello world' is given in the [quick start](https://macrame.fruitbat.studio/Quick_Start.html).

## Requirements
Macrame is a pure PHP library. The requirements are:

* **PHP 7.4 or higher** 
* **`posix` extension**
* **`mbstring` extension**

Since Macrame is for building command-line applications, it can be difficult to guarantee that the host system meets the minimum requirements. To address this, Macrame provides a `preflight()` function to validate that the host can run Macrame scripts.

## Contributing
Contributions submitted via pull request are welcome and will be fully credited. 

* **Use [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) style**: Using [php-cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) is recommended, but any tool is fine. Do not use windows line endings.
* **Use [gitflow](https://www.geeksforgeeks.org/git-flow/)**: Create a new feature branch from `develop` for all new feature work.
* **One pull request per feature/fix**: Pull requests should only contain work for one feature or fix.
* **Use static analysis ([phpstan](https://phpstan.org/))**: All code should pass `phpstan` at level 6. Use the `phpstan.neon` in the repository.
* **Update documentation if needed**: If documentation is required, update the [daux](https://daux.io/) pages in the `documentation/` directory.
* **Write tests ([phpunit](https://phpunit.de/index.html))**: Achieving 100% coverage is difficult, especially for anything that uses animations or forks child processes, but please try.



