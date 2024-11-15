Macrame can download files from the internet to a local file and show a progress animation to the user that actually measures progress.

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#downloading-a-file">Downloading a file</a><br>
<a href="#setting-the-destination-file">Setting the destination the file</a><br>
<a href="#getting-the-destination-file">Getting the destination the file</a><br>
<a href="#customizing-the-progress-bar">Customizing the progress bar</a><br>
<a href="#basic-auth">Basic auth</a><br>
</div>

# Quickref
```PHP
$macrame = new Macrame();

$url = "https://example.ca/path/to/file";

// download file
$macrame->download($url)->get();

// download file and get boolean of success status
$macrame->download($url)->succeeded();

// download file and get MacrameFile object of destination
$macrame->download($url)->get()->getOut();
$macrame->download($url)->get()->getOutfile();

// set destination file
$macrame->download($url)->out('/path/to/destination/file')->get();
$macrame->download($url)->outfile('/path/to/destination/file')->get(); // synonym for 'out'

// set char used in progress bar
$macrame->progressChar('💾');

// get remote size of file in bytes
$macrame->size();

// set basic auth credentials
$macrame->auth('username', 'password');
```

# Downloading a file
Macrame provides the `download()` method to accept a url as a string and return an object of `MacrameDownload`. The `MacrameDownload` object exposes the `get()` method to initiate the download and show the progress animation.

Download files are saved to the current working directory with the filename from the url.

```PHP
$macrame = new Macrame();

$url = "https://example.ca/path/to/file";

$macrame->download($url)->get();
```

Note that the url argument to `download()` must be a valid url and include the scheme, ie 'https'.

If the url is unreachable for any reason, macrame will output the error:

```
[ERROR] Remote file is unreachable
```

If any error ocurrs, the destination file will not be created.

The success status of the download is returned as a boolean by the `succeeded()` method.

```PHP
$dl = $macrame->download($url);
$dl->get();

if($dl->succeeded()) {
    // success
}

```

# Setting the destination file
A custom destination path can be set with the `out()` method.

```PHP
$macrame->download($url)->out('/path/to/destination/file')->get();
// or
$macrame->download($url)->outfile('/path/to/destination/file')->get(); // synonym for out()
```

Destination paths are validated when `get()` is called. The validations are:

* The directory of the path must exist. Directories will not be created by Macrame.
* The directory of the path must be writable.
* The file must not already exist. Macrame will not overwrite.
* There must be enough space on the target partition for the file.

If any validation fails, the download will not be attempted and Macrame will output an error similar to:

```
[ERROR] Download error. Cannot write to target file at /path/to/file
```

If a destination file path has not been set, Macrame will download the file to the current working directory with the filename from the url.

# Getting the destination file
Once the download is complete, the destination output file can be retreived with the `getOut()` method which returns an object of `MacrameFile`.

```PHP
$macrame->download($url)->get()->getOut();
// or
$macrame->download($url)->get()->getOutfile(); // synonym for getOut()
```

Since the return type of `getOut()` is `MacrameFile`, all `MacrameFile` methods can be used:

```PHP
$out = $macrame->download($url)->get()->getOut();

// get the full path of the output file
$outputFilePath = $out->path();

// read all contents into memory
$outContents = $out->read();

// read file one line at a time using a generator
foreach($out->byLines() as $line) {
    // one line
}

// set file to be deleted when script ends
$out->deleteOnExit();
```

# Customizing the progress bar
By default, the progress bar that is shown to the user during downloads is comprised of `#` characters. It looks something like this:

```
Downloading filename 50.00% #########################################
```

The default character can be changed using the `progressChar()` method:

```PHP
$macrame->download($url)->progressChar('.')->get();
```

Although a string of any length can be used and Macrame will compensate to ensure the progress bar does not run over the width of the terminal, it is advised to use only a single character.

The `progressChar()` method is multibyte safe.

# Basic auth
Files can be downloaded from urls that require basic authentication by using the `auth()` method. This method accepts a username and a password as arguments.

```PHP
$macrame->download($url)->auth('username', 'password')->get();
```




