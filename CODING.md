
[Permalink](http://kohanaframework.org/3.3/guide/kohana/conventions "Permalink to Conventions and Style | Kohana User Guide")

# Conventions and Style | Kohana User Guide

[Conventions and Coding Style][1]  
&nbsp;&nbsp;&nbsp;&nbsp; [Class Names and File Location][2]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Examples][3]  
&nbsp;&nbsp;&nbsp;&nbsp; [Coding Standards][4]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Brackets][5]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Curly Brackets][6]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Class Brackets][7]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Empty Brackets][8]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Array Brackets][9]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Opening Parenthesis][10]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Closing parenthesis][11]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Single Dimension][12]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Multidimensional][13]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Arrays as Function Arguments][14]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Naming Conventions][15]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Classes][16]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Functions and Methods][17]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Variables][18]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Indentation][19]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [String Concatenation][20]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Single Line Statements][21]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Comparison Operations][22]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Switch Structures][23]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Parentheses][24]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Ternaries][25]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Type Casting][26]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Constants][27]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Comments][28]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [One-line Comments][29]  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [Regular Expressions][30]  


It is encouraged that you follow Kohana's coding style. This makes code more readable and allows for easier code sharing and contributing.

## Class Names and File Location

Class names in Kohana follow a strict convention to facilitate [autoloading][31]. Class names should have uppercase first letters with underscores to separate words. Underscores are significant as they directly reflect the file location in the filesystem.

The following conventions apply:

1.  CamelCased class names should be used when it is undesirable to create a new directory level.
2.  All class file names and directory names must match the case of the class as per [PSR-0][32].
3.  All classes should be in the `classes` directory. This may be at any level in the [cascading filesystem][33].

### Examples

Remember that in a class, an underscore means a new directory. Consider the following examples:

Class Name

File Path

Controller_Template

classes/Controller/Template.php

Model_User

classes/Model/User.php

Model_BlogPost

classes/Model/BlogPost.php

Database

classes/Database.php

Database_Query

classes/Database/Query.php

Form

classes/Form.php

## Coding Standards

In order to produce highly consistent source code, we ask that everyone follow the coding standards as closely as possible.

### Brackets

Please use [BSD/Allman Style][34] bracketing.

#### Curly Brackets

Curly brackets are placed on their own line, indented to the same level as the control statement.

    // Correct
    if ($a === $b)
    {
        ...
    }
    else
    {
        ...
    }
    
    // Incorrect
    if ($a === $b) {
        ...
    } else {
        ...
    }
    

#### Class Brackets

The only exception to the curly bracket rule is, the opening bracket of a class goes on the same line.

    // Correct
    class Foo {
    
    // Incorrect
    class Foo
    {
    

#### Empty Brackets

Don't put any characters inside empty brackets.

    // Correct
    class Foo {}
    
    // Incorrect
    class Foo { }
    

#### Array Brackets

Arrays may be single line or multi-line.

    array('a' =&gt; 'b', 'c' =&gt; 'd')
    
    array(
        'a' =&gt; 'b', 
        'c' =&gt; 'd',
    )
    

##### Opening Parenthesis

The opening array parenthesis goes on the same line.

    // Correct
    array(
        ...
    )
    
    // Incorrect:
    array
    (
        ...
    )
    

##### Closing parenthesis

###### Single Dimension

The closing parenthesis of a multi-line single dimension array is placed on its own line, indented to the same level as the assignment or statement.

    // Correct
    $array = array(
        ...
    )
    
    // Incorrect
    $array = array(
        ...
        )
    

###### Multidimensional

The nested array is indented one tab to the right, following the single dimension rules.

    // Correct
    array(
        'arr' =&gt; array(
            ...
        ),
        'arr' =&gt; array(
            ...
        ),
    )
    
    array(
        'arr' =&gt; array(...),
        'arr' =&gt; array(...),
    )
    

##### Arrays as Function Arguments

    // Correct
    do(array(
        ...
    ))
    
    // Incorrect
    do(array(
        ...
        ))
    

As noted at the start of the array bracket section, single line syntax is also valid.

    // Correct
    do(array(...))
    
    // Alternative for wrapping long lines
    do($bar, 'this is a very long line',
        array(...));
    

### Naming Conventions

Kohana uses under_score naming, not camelCase naming.

#### Classes

    // Controller class, uses Controller_ prefix
    class Controller_Apple extends Controller {
    
    // Model class, uses Model_ prefix
    class Model_Cheese extends Model {
    
    // Regular class
    class Peanut {
    

When creating an instance of a class, don't use parentheses if you're not passing something on to the constructor:

    // Correct:
    $db = new Database;
    
    // Incorrect:
    $db = new Database();
    

#### Functions and Methods

Functions should be all lowercase, and use under_scores to separate words:

    function drink_beverage($beverage)
    {
    

#### Variables

All variables should be lowercase and use under_score, not camelCase:

    // Correct:
    $foo = 'bar';
    $long_example = 'uses underscores';
    
    // Incorrect:
    $weDontWantThis = 'understood?';
    

### Indentation

You must use tabs to indent your code. Using spaces for tabbing is strictly forbidden.

Vertical spacing (for multi-line) is done with spaces. Tabs are not good for vertical alignment because different people have different tab widths.

    $text = 'this is a long text block that is wrapped. Normally, we aim for '
          .'wrapping at 80 chars. Vertical alignment is very important for '
          .'code readability. Remember that all indentation is done with tabs,'
          .'but vertical alignment should be completed with spaces, after '
          .'indenting with tabs.';
    

### String Concatenation

Do not put spaces around the concatenation operator:

    // Correct:
    $str = 'one'.$var.'two';
    
    // Incorrect:
    $str = 'one'. $var .'two';
    $str = 'one' . $var . 'two';
    

### Single Line Statements

Single-line IF statements should only be used when breaking normal execution (e.g. return or continue):

    // Acceptable:
    if ($foo == $bar)
        return $foo;
    
    if ($foo == $bar)
        continue;
    
    if ($foo == $bar)
        break;
    
    if ($foo == $bar)
        throw new Exception('You screwed up!');
    
    // Not acceptable:
    if ($baz == $bun)
        $baz = $bar %2B 2;
    

### Comparison Operations

Please use OR and AND for comparison:

    // Correct:
    if (($foo AND $bar) OR ($b AND $c))
    
    // Incorrect:
    if (($foo &amp;&amp; $bar) || ($b &amp;&amp; $c))
    

Please use elseif, not else if:

    // Correct:
    elseif ($bar)
    
    // Incorrect:
    else if($bar)
    

### Switch Structures

Each case, break and default should be on a separate line. The block inside a case or default must be indented by 1 tab.

    switch ($var)
    {
        case 'bar':
        case 'foo':
            echo 'hello';
        break;
        case 1:
            echo 'one';
        break;
        default:
            echo 'bye';
        break;
    }
    

### Parentheses

There should be one space after statement name, followed by a parenthesis. The ! (bang) character must have a space on either side to ensure maximum readability. Except in the case of a bang or type casting, there should be no whitespace after an opening parenthesis or before a closing parenthesis.

    // Correct:
    if ($foo == $bar)
    if ( ! $foo)
    
    // Incorrect:
    if($foo == $bar)
    if(!$foo)
    if ((int) $foo)
    if ( $foo == $bar )
    if (! $foo)
    

### Ternaries

All ternary operations should follow a standard format. Use parentheses around expressions only, not around just variables.

    $foo = ($bar == $foo) ? $foo : $bar;
    $foo = $bar ? $foo : $bar;
    

All comparisons and operations must be done inside of a parentheses group:

    $foo = ($bar &gt; 5) ? ($bar %2B $foo) : strlen($bar);
    

When separating complex ternaries (ternaries where the first part goes beyond ~80 chars) into multiple lines, spaces should be used to line up operators, which should be at the front of the successive lines:

    $foo = ($bar == $foo)
         ? $foo
         : $bar;
    

### Type Casting

Type casting should be done with spaces on each side of the cast:

    // Correct:
    $foo = (string) $bar;
    if ( (string) $bar)
    
    // Incorrect:
    $foo = (string)$bar;
    

When possible, please use type casting instead of ternary operations:

    // Correct:
    $foo = (bool) $bar;
    
    // Incorrect:
    $foo = ($bar == TRUE) ? TRUE : FALSE;
    

When casting type to integer or boolean, use the short format:

    // Correct:
    $foo = (int) $bar;
    $foo = (bool) $bar;
    
    // Incorrect:
    $foo = (integer) $bar;
    $foo = (boolean) $bar;
    

### Constants

Always use uppercase for constants:

    // Correct:
    define('MY_CONSTANT', 'my_value');
    $a = TRUE;
    $b = NULL;
    
    // Incorrect:
    define('MyConstant', 'my_value');
    $a = True;
    $b = null;
    

Place constant comparisons at the end of tests:

    // Correct:
    if ($foo !== FALSE)
    
    // Incorrect:
    if (FALSE !== $foo)
    

This is a slightly controversial choice, so I will explain the reasoning. If we were to write the previous example in plain English, the correct example would read:

    if variable $foo is not exactly FALSE
    

And the incorrect example would read:

    if FALSE is not exactly variable $foo
    

Since we are reading left to right, it simply doesn't make sense to put the constant first.

Use //, preferably above the line of code you're commenting on. Leave a space after it and start with a capital. Never use #.

    // Correct
    
    //Incorrect
    // incorrect
    # Incorrect
    

### Regular Expressions

When coding regular expressions please use PCRE rather than the POSIX flavor. PCRE is considered more powerful and faster.

    // Correct:
    if (preg_match('/abc/i', $str))
    
    // Incorrect:
    if (eregi('abc', $str))
    

Use single quotes around your regular expressions rather than double quotes. Single-quoted strings are more convenient because of their simplicity. Unlike double-quoted strings they don't support variable interpolation nor integrated backslash sequences like n or t, etc.

    // Correct:
    preg_match('/abc/', $str);
    
    // Incorrect:
    preg_match("/abc/", $str);
    

When performing a regular expression search and replace, please use the $n notation for backreferences. This is preferred over n.

    // Correct:
    preg_replace('/(d%2B) dollar/', '$1 euro', $str);
    
    // Incorrect:
    preg_replace('/(d%2B) dollar/', '\1 euro', $str);
    

Finally, please note that the $ character for matching the position at the end of the line allows for a following newline character. Use the D modifier to fix this if needed. [More info][35].

    $str = "[email&nbsp;protected]n";
    
    preg_match('/^.%2B@.%2B$/', $str);  // TRUE
    preg_match('/^.%2B@.%2B$/D', $str); // FALSE

 [1]: http://kohanaframework.org#conventions-and-coding-style
 [2]: http://kohanaframework.org#class-names-and-file-location
 [3]: http://kohanaframework.org#class-name-examples
 [4]: http://kohanaframework.org#coding-standards
 [5]: http://kohanaframework.org#brackets
 [6]: http://kohanaframework.org#curly-brackets
 [7]: http://kohanaframework.org#class-brackets
 [8]: http://kohanaframework.org#empty-brackets
 [9]: http://kohanaframework.org#array-brackets
 [10]: http://kohanaframework.org#opening-parenthesis
 [11]: http://kohanaframework.org#closing-parenthesis
 [12]: http://kohanaframework.org#single-dimension
 [13]: http://kohanaframework.org#multidimensional
 [14]: http://kohanaframework.org#arrays-as-function-arguments
 [15]: http://kohanaframework.org#naming-conventions
 [16]: http://kohanaframework.org#classes
 [17]: http://kohanaframework.org#functions-and-methods
 [18]: http://kohanaframework.org#variables
 [19]: http://kohanaframework.org#indentation
 [20]: http://kohanaframework.org#string-concatenation
 [21]: http://kohanaframework.org#single-line-statements
 [22]: http://kohanaframework.org#comparison-operations
 [23]: http://kohanaframework.org#switch-structures
 [24]: http://kohanaframework.org#parentheses
 [25]: http://kohanaframework.org#ternaries
 [26]: http://kohanaframework.org#type-casting
 [27]: http://kohanaframework.org#constants
 [28]: http://kohanaframework.org#comments
 [29]: http://kohanaframework.org#one-line-comments
 [30]: http://kohanaframework.org#regular-expressions
 [31]: http://kohanaframework.org/3.3/guide/kohana/autoloading
 [32]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 [33]: http://kohanaframework.org/3.3/guide/kohana/files
 [34]: http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style
 [35]: http://blog.php-security.org/archives/76-Holes-in-most-preg_match-filters.html  
