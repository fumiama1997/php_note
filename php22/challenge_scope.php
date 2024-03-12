<pre>
<?php
$a = 10;
$b = 10;
$c = 10;
$d = 10;
define('DEF', 10);

print 'fuga_before: a = ' . $a . "\n";
print 'fuga_before: b = ' . $b . "\n";
print 'fuga_before: c = ' . $c . "\n";
print 'fuga_before: d = ' . $d . "\n";
print 'fuga_before: DEF = ' . DEF  . "\n";

fuga($c);

print 'fuga_after: a = ' . $a . "\n";
print 'fuga_after: b = ' . $b . "\n";
print 'fuga_after: c = ' . $c . "\n";
print 'fuga_after: d = ' . $d . "\n";
print 'fuga_after: DEF = ' . DEF  . "\n";

function fuga($c)
{

    global $d;

    $a++;
    print 'fuga: a = ' . $a . "\n";

    $b = 100;
    $b++;
    print 'fuga: b = ' . $b . "\n";

    $c++;
    print 'fuga: c = ' . $c . "\n";

    $d++;
    print 'fuga: d = ' . $d . "\n";

    define('DEF', 100);
    print 'fuga: DEF = ' . DEF . "\n";
}
?>
</pre>