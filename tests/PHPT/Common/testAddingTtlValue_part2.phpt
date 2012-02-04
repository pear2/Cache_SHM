--TEST--
Tests adding a TTL-ed value, part 2
--DESCRIPTION--
In part 2, we check to see we still have a value, and sleep() until it expires.
--SKIPIF--
<?php
if ('cli' === PHP_SAPI) {
    die('Skip: Using CLI');
}
?>
--FILE--
<?php
namespace PEAR2\Cache;
require_once '_runner.inc';

$adapterName = __NAMESPACE__ . '\SHM\Adapter\\' . $adapter;
$object = new SHM(new $adapterName('TEST'));

\assertSame('value', $object->get('key'), __FILE__);
sleep(3);
?>
--EXPECT--
