--TEST--
Tests adding a TTL-ed value, part 2
--DESCRIPTION--
In part 2, we check to see we still have a value, and sleep() until it expires.
--FILE--
<?php
require_once '../includes/runner.php';

if ('cli' === PHP_SAPI) {
    die();//Added here, because the SKIP section is always "cli", but not this.
}

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

assertSame('value', $object->get('key'), __FILE__);
sleep(3);
?>
--EXPECT--
