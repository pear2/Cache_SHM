--TEST--
Tests adding a value
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame(true, $object->add('key', 'value'), __FILE__);
assertSame(false, $object->add('key', 'value'), __FILE__);
?>
--EXPECT--
