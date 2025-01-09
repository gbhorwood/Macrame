This example goes over writing a script that offers the user a choice of Linux distributions to download and downloading the selected one. The user is presented with an interactive menu of distributions to choose from, the size in MB of the distribution iso is presented to the user for a Y/N approval, the file is then downloaded with a progress bar display, and the path to the downloaded is is displayed.

# Features used
This recipie uses the following Macrame features:
* [interactive menus](../04_Manual/05_Menus_and_Such.md)
* [downloading files](../04_Manual/08_Downloading_files.md)
* [keydown input](../04_Manual/04_Getting_User_Text_Input.md)

# Example
```PHP
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Gbhorwood\Macrame\Macrame;

$macrame = new Macrame("download an iso");

if ($macrame->running()) {

    /**
     * Date for menu
     */
    $header = "Choose a classic distro";
    // note: these urls are for example purposes only.
    $isos = [
        "Yggdrasil" => "https://example.ca/isos/yggdrasil.iso",
        "Hannah Montana" => "https://example.ca/isos/hannahmontana.iso",
        "Yellow Dog" => "https://example.ca/isos/yellowdog.iso",
    ];


    /**
     * Read menu choice
     */
    $distro = $macrame->menu()
                      ->interactive(array_keys($isos), $header);

    $url = $isos[$distro];

    /**
     * Create MacrameDownload object
     */
    $dl = $macrame->download($url);

    /**
     * Get remote file size
     */
    $fileSizeMb =  round($dl->size() / 1024 / 1024);

    /**
     * Get user's approval
     */
    $approve = $macrame->input()
                       ->isOneOf(['Y', 'y', 'N', 'n'])
                       ->readKey("File is {$fileSizeMb}MB. Is this okay? [YN]");

    if(strtolower($approve) == 'n') {
        $macrame->text('Exiting')->write();
        $macrame->exit();
    }

    /**
     * Download file
     */
    $dl->get();

    /**
     * Handle success or error
     */
    if ($dl->succeeded()) {
        $outfilePath = $dl->getOutfile()->path();
        $macrame->text("ISO at: ".$outfilePath)->write();
    } else {
        $macrame->text("An error ocurred downloading")->error();
    }

    // exit cleanly
    $macrame->exit();
}
```

# Walkthrough
An interactive menu is created and displayed using:

```PHP
$distro = $macrame->menu()
                  ->interactive(array_keys($isos), $header);
```

The `interactive()` method takes two arguments: the array of options to display (in this example the names of the distros), and a string to display as a header on the menu. The return value is the string the user selected.


The `MacrameDownload` object is created here with the url of the iso as its argument. Note that the download is not started until the `get()` method is called.

```PHP
$dl = $macrame->download($url);
```

Calling `size()` on the `MacrameDownload` object returns the size, in bytes, of the remote file. This can be done before the download is initiated.

```PHP
$fileSizeMb =  round($dl->size() / 1024 / 1024);
```

The user is polled to approve the download by pressing either the 'y' or 'n' keys. The `readKey()` method of `MacrameInput` reads keydown events and the `isOneOf()` validator continues to poll the user until the input is one of the elements of array argument. The character of the keydown is returned.

```PHP
$approve = $macrame->input()
                   ->isOneOf(['Y', 'y', 'N', 'n'])
                   ->readKey("File is {$fileSizeMb}MB. Is this okay? [YN]");
```

If the key the user pressed is 'n', we write 'Exiting' to `STDOUT` using `MacrameText` and then exit the script cleanly.

```PHP
if(strtolower($approve) == 'n') {
    $macrame->text('Exiting')->write();
    $macrame->exit();
}
```

The file is downloaded using the `get()` method of `MacrameDownload`.

```PHP
$dl->get();
```

The download is a blocking call. Once it is complete, success can be confirmed by calling the `succeeded()` method of `MacrameDownload`.

The full path of the iso file on local disk is obtained by calling `getOutfile()->path()` on the `MacrameDownload` object.

```PHP
if ($dl->succeeded()) {
    $outfilePath = $dl->getOutfile()->path();
    $macrame->text("ISO at: ".$outfilePath)->write();
} else {
    $macrame->text("An error ocurred downloading")->error();
}
```
