--TEST--
Tests adding a TTL-ed value, part 3
--DESCRIPTION--
In part 3, we check to verify we no longer have a value.
--FILE--
<?php
namespace PEAR2\Cache\SHM\Adapter;
require_once '_runner.inc';

$adapterName = __NAMESPACE__ . '\\' . $adapter;
$object = new $adapterName('TEST');

try {
    $object->get('key');
    echo 'TTL value part 3: key did not expire.';
} catch(\Exception $e) {
    \assertSame(
        isset($_GET['nokeycode']) ? (int) $_GET['nokeycode'] : 200,
        $e->getCode(), __FILE__
    );
}
?>
--EXPECT--
