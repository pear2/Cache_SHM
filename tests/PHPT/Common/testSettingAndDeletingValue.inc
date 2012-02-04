<?php
namespace PEAR2\Cache;

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

\assertSame(true, $object->set('key', 'value'), __FILE__);
\assertSame(true, $object->delete('key'), __FILE__);
\assertSame(false, $object->delete('key'), __FILE__);