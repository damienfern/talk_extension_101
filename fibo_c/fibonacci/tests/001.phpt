--TEST--
Check if fibonacci is loaded
--EXTENSIONS--
fibonacci
--FILE--
<?php
echo 'The extension "fibonacci" is available';
?>
--EXPECT--
The extension "fibonacci" is available
