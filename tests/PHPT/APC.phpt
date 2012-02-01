--TEST--
APC tests.
--DESCRIPTION--
Sets up the settings for the APC adapter, and executes its tests.
--CGI--
--FILE--
<?php
$adapter = 'APC';

require_once '_runner.inc';
?>
--EXPECT--
