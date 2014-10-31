--TEST--
Tests adding a TTL-ed value, part 1
--DESCRIPTION--
In part 1, we set the value.
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame(true, $object->add('key', 'value', 2), __FILE__);
assertSame(false, $object->add('key', 'value'), __FILE__);
assertSame('value', $object->get('key'), __FILE__);
?>
--EXPECT--
