--TEST--
Tests seting and deleting a value
--FILE--
<?php
namespace PEAR2\Cache\SHM\Adapter;
require_once '_runner.inc';

$adapterName = __NAMESPACE__ . '\\' . $adapter;
$object = new $adapterName('TEST');

\assertSame(true, $object->set('key', 'value'), __FILE__);
\assertSame(true, $object->delete('key'), __FILE__);
\assertSame(false, $object->delete('key'), __FILE__);
?>
--EXPECT--
