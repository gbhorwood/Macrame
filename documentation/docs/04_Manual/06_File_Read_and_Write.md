File handling in command line applications has different challenges than for web applications. In the web environment, the developer knows the filesystem structure and permissions and read and write files with confidence. Command line applications, however, run on someone else's compture where no such assurances can be made.

Macrame provides a number of file access methods to help ensure more-safe interaction with the file system

<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
<a href="#creating-a-file-object">Creating a file object</a><br>
<a href="#writing-to-files">Writing to files</a><br>
<a href="#appending-to-an-existing-file">Appending to an existing file</a><br>
<a href="#creating-a-new-file">Creating a new file</a><br>
<a href="#reading-a-file">Reading a file</a><br>
<a href="#reading-very-large-files">Reading very large files</a><br>
<a href="#cleaning-up-files-on-exit">Cleaning up files on exit</a><br>
</div>

# Quickref

```PHP
$macrame = new Macrame();

// string. Read file as a string
$macrame->file('/dir/file')->read();

// generator. Iterate over a file line-by-line
foreach($macrame->file('/dir/file')->byLines() as $line) {
    // one line
}

// bool. Write content to file 
$macrame->file('/dir/file')->write('content');

// bool. Test if file can be read
if($macrame->file('/dir/file')->readable()) {
    // can read file
}

// bool. Test if file can be written to
if($macrame->file('/dir/file')->writable()) {
    // can write to file
}

// bool. Test if file exists and writing would overwrite
if($macrame->file('/dir/file')->clobbers()) {
    // file exists.
}

// bool. Synonym for clobber
$macrame->file('/dir/file')->exists();

// bool. Test if the device has enough space to write content to file
$macrame->file('/dir/file')->enoughSpace('content');

// int. Get the size of the file in bytes
$macrame->file('/dir/file')->byteCount();

// set a file to be deleted when Macrame's 'exit' function called
$macrame->file('/dir/file')->deleteOnExit();
$macrame->file('/dir/file')->deleteOnQuit(); // synonym
```

# Creating a file object
Macrame provides the `file()` method to accept a path to a file and return a `MacrameFile` object. The `MacrameFile` object exposes methods to create, read or write the file at that path.

```PHP
$macrame = new Macrame();

$myFile = $macrame->file('/path/to/my/file');
```
All methods are chainable. So, for instance, to create a file object and read the contents of the file, one could:

```PHP
$macrame = new Macrame();

$myFileContents = $macrame->file('/path/to/my/file')->read();
```

File paths expand `~` to the user's home directory.

# Writing to files
Macrame allows writing a string to a file with the `write()` method.

```PHP
$someString = "Hello world";

$macrame->file('/path/to/my/file')->write($someString); // return bool
```

If `write()` succeeds it returns `true`. On any error `false` is returned.

The `write()` method tests if the target file has write permissions and if there is enough space on the target partition to write the content. Any failure results in a warning output to the consle and a return value of `false`. If the file does not exist, `write()` attempts to create it.

## Testing for writability
The write permissions of a file can be explicitly tested before writing with the `writable()` method.

```PHP
$myFile = $macrame->file('/path/to/a/file');

if(!$myFile->writable()) {
    // no write permissions
}
```

The `writable()` method returns `true` if the file permissions allow writing, `false` otherwise.

## Testing for clobber
Writing to a file that already exists will overwrite it. If that is not desired, the existence of a file can be tested using the `clobbers()` method or it's synonym `exists()`.

```PHP
$myFile = $macrame->file('/path/to/a/file');

if($myFile->clobbers()) {
    // writing will overwrite existing file
}

// synonym for clobbers()
if($myFile->exists()) {
    // writing will overwrite existing file
}
```

The `clobbers()` and `exists()` methods return `true` if the file exists, `false` otherwise.

## Testing for disk space
Attempting to write more bytes than the file's partition has available is bad and should be avoided. The `enoughSpace()` method allows for testing if there are enough free bytes to write the desired content.

```PHP
$myFile = $macrame->file('/path/to/a/file');
$myContent = "Hello world";

if(!$myFile->enoughSpace($myContent)) {
    // not enough free space on partition to write content
}
```

The `enoughSpace()` method returns `true` if the target partition has enough free bytes to allow writing, `false` otherwise.

## Putting it together
Testing for writability can be done either by calling each test method and displaying custom error messages, or by just calling the `write()` function and letting Macrame output the default warnings.

```PHP
$macrame = new Macrame();

$myContent = "Hello world";

/**
 * Call each test before writing
 */
$myFile = $macrame->file('/path/to/file');

// test for permissions
if (!$myFile->writable()) {
    $macrame->text("File not writable")->error();
}
// test if target file will be overwritten
else if ($myFile->clobbers()) {
    $macrame->text("File already exists")->error();
}
// test if there's enough disk space
else if (!$myFile->enoughSpace($myContent)) {
    $macrame->text("Not enough disk space")->error();
}
// write to file
else {
    $myFile->write($myContent);
}

/**
 * Or
 * Use tests done by write() with default error messages
 */
if(!$macrame->file('/path/to/file')->write($myContent)) {
    // write failed. warnings displayed.
}
```

Note that if you rely on `write()` to test writability it will output default warnings to the screen and return `false` on failure.

The `write()` method does not test if the target file will be overwritten.


# Appending to an existing file
Strings can be appended to files using the `append()` method.

```PHP
$someString = "Hello world";

$macrame->file('/path/to/my/file')->append($someString); // return bool
```

If `append()` succeeds it returns `true`. On any error `false` is returned.

The `append()` method tests if the target file has write permissions and if there is enough space on the target partition to write the content. Any failure results in a warning output to the consle and a return value of `false`. If the target file does not exist, `append()` will attempt to create it.

# Creating a new file
Creating a new file without writing to it can be done with the `create()` method.

```PHP
$macrame = new Macrame();

$macrame->file('/path/to/file')->create();
```

The directory structure will be created recursively.

If there is a permissions error or an error because the root directory does not exist, `create()` will display a warning. The `create()` method is chainable.

# Reading a file
Reading the contents of a file into a string can be done with the `read()` method.

```PHP
$macrame = new Macrame();

$contents = $macrame->file('/path/to/file')->read();
```

If the file is unreadable due to permissions or because it does not exist, a warning will be displayed and `null` will be returned.

## Testing for readability
Read access to a file can be tested using the `readable()` method.

```PHP
$macrame = new Macrame();

if($macrame->file('/path/to/file')->readable()) {
    // can read the file
}
```

The `readable()` method returns `true` if the file exists and has permissions that grant read access, `false` otherwise.

# Reading very large files 
If a file is larger than the amount of memory available to PHP, reading its contents into a variable can cause errors. Reading large files can be done by iterating over them line-by-line using the `byLine()` method.

```PHP
$macrame = new Macrame();

foreach($macrame->file('/large/file')->byLine() as $line) {
    // line of file at $line
}
```

If the file read with `byLine()` does not exist or the user does not have read permission, a warning will be displayed and null will be returned.


# Cleaning up files on exit
Files can be set to be automatically deleted when the script exits by using the `deleteOnQuit()` method.

```PHP
$macrame = new Macrame();

$macrame->file('/path/to/file')->deleteOnQuit();

$macrame->exit();
```

Files will be removed from the filesystem when `$macrame->exit()` is called. If the file cannot be deleted because it has become missing or had its permissions changed during execution of the script, `$macrame->exit()` will error silently.
