--TEST--
test1() Basic test
--EXTENSIONS--
fibonacci
--FILE--
<?php
$ret = test1();

var_dump($ret);
?>
--EXPECT--
The extension fibonacci is loaded and working!
NULL
