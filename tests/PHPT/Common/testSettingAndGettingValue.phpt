--TEST--
Tests setting and getting a value
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame(true, $object->set('key', 'value'), __FILE__);
assertSame('value', $object->get('key'), __FILE__);
?>
--EXPECT--
