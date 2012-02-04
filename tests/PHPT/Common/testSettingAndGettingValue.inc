<?php
namespace PEAR2\Cache;

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

\assertSame(true, $object->set('key', 'value'), __FILE__);
\assertSame('value', $object->get('key'), __FILE__);