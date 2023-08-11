
<div style='background-color:#F5F2F0; border-left: solid #808080 4px; border-radius: 4px; padding-left:0.5em; padding-bottom:0.5em; margin-top:0.5em; margin-bottom:0.5em; margin-right:-20px'>
<div style="width:100%; text-align:right;padding-right:30px"><a style="text-decoration: none; font-size: large;"><b>Contents</b></a></div>
<a href="#quickref">Quickref</a><br>
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
```
