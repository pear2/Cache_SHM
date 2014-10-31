--TEST--
Tests locking and unlocking within a single file
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame(true, $object->lock('key'), __FILE__);
assertSame(false, $object->lock('key'), __FILE__);
assertSame(true, $object->unlock('key'), __FILE__);
assertSame(false, $object->unlock('key'), __FILE__);

assertSame(true, $object->lock('key'), __FILE__);
assertSame(false, $object->lock('key'), __FILE__);
assertSame(true, $object->unlock('key'), __FILE__);
assertSame(false, $object->unlock('key'), __FILE__);
?>
--EXPECT--
