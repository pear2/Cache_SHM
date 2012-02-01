<?php
namespace PEAR2\Cache;

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

assertSame(true, $object->add('key', 'value'), __FILE__);
assertSame(false, $object->add('key', 'value'), __FILE__);