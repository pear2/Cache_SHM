--TEST--
Tests adding a value
--FILE--
<?php
namespace PEAR2\Cache\SHM\Adapter;
require_once '_runner.inc';

$adapterName = __NAMESPACE__ . '\\' . $adapter;
$object = new $adapterName('TEST');

\assertSame(true, $object->add('key', 'value'), __FILE__);
\assertSame(false, $object->add('key', 'value'), __FILE__);
?>
--EXPECT--
