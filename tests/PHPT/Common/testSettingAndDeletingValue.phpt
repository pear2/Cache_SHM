--TEST--
Tests seting and deleting a value
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame(true, $object->set('key', 'value'), __FILE__);
assertSame(true, $object->delete('key'), __FILE__);
assertSame(false, $object->delete('key'), __FILE__);
?>
--EXPECT--
