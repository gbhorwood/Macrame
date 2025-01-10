<p />
<div class="larger">
Macrame is library for building interactive command line scripts in PHP.
</div><p />

Macrame was originally written as an internal project for <a href="https://fruitbat.studio/">Fruitbat Studios</a>, Cloverhitch Technologies and Kludgetastic Implementations.

# Features

With Macrame you can:

## Output formatted and stylized text
Apply ANSI colours and styles to text using Macrame's methods or custom tag system. Align text blocks in the terminal or wrap it to the terminal width on word breaks. Page long text output. [docs](04_Manual/03_Styled_Text_Output.md)

## Handle command line arguments
Quickly access and test all command line arguments, switches and assignments. Handle duplicate switches and arguments so that things like `-vvv` and `-v -v -v` are treated the same. [docs](04_Manual/02_Handling_Arguments.md)

## Create interactive menus
Make interactive menus driven by arrow and tab keys; both vertical and horizontal. Apply custom styling and alignment to menus. There's even a datepicker that behaves the way you think a datepicker should. [docs](04_Manual/05_Menus_and_Such.md)

## Read user input
Read a line of user input, or a key down event. Apply input validators to keep prompting until your user gives you good data. Echo dots if the input is sensitive, like passwords. Read data from `STDIN` so your script can accept piped input. [docs](04_Manual/04_Getting_User_Text_Input.md)

## Do safer file reads and writes
Read and write files with easy handling of permissions and disk space errors. Set files to be automatically deleted when your script terminates. [docs](04_Manual/06_File_Read_and_Write.md)

## Show spinners while your script does work in the background
Show an animated spinner while running a time-intensive function in the background. Choose from forty different spinner options. [docs](04_Manual/09_Spinners_and_Tasks.md)

## Output data in ASCII tables
Display array data as an ASCII table, kind of like the ones MySql outputs. Set table border styles, configure column alignment and table alignment in the terminal. Newlines and tabs are automatically handled. [docs](04_Manual/07_Table_Output.md)

## Download files with real progress bars
Download a file from an url and show the user a progress bar that actually tracks the download progress. [docs](04_Manual/08_Downloading_files.md)

## Build fancy figlet-style headlines
Create and output large headlines using [figlet](http://www.figlet.org/)-style fonts. [docs](04_Manual/10_Headlines_with_Figlet.md)

---
