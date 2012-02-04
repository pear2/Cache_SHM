<?php
namespace PEAR2\Cache;

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

\assertSame(true, $object->lock('key'), __FILE__);
\assertSame(false, $object->lock('key'), __FILE__);
\assertSame(true, $object->unlock('key'), __FILE__);
\assertSame(false, $object->unlock('key'), __FILE__);

\assertSame(true, $object->lock('key'), __FILE__);
\assertSame(false, $object->lock('key'), __FILE__);
\assertSame(true, $object->unlock('key'), __FILE__);
\assertSame(false, $object->unlock('key'), __FILE__);