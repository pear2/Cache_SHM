<?php
namespace PEAR2\Cache;

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

\assertSame(true, $object->add('key', 'value', 2), __FILE__);
\assertSame(false, $object->add('key', 'value'), __FILE__);
\assertSame('value', $object->get('key'), __FILE__);
sleep(2);
try {
    $object->get('key');
    echo 'FAIL!';
} catch(Exception $e) {
    \assertSame(200, $e->getCode());
}