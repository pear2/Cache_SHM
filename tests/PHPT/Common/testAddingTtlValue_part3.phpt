--TEST--
Tests adding a TTL-ed value, part 3
--DESCRIPTION--
In part 3, we check to verify we no longer have a value.
--FILE--
<?php
require_once '../includes/runner.php';

$adapterName = 'PEAR2\Cache\SHM\Adapter\\' . $adapter;
$object = new $adapterName('TEST');

try {
    $object->get('key');
    echo 'TTL value part 3: key did not expire.';
} catch(Exception $e) {
    assertSame(
        isset($_GET['nokeycode']) ? (int) $_GET['nokeycode'] : 200,
        $e->getCode(), __FILE__
    );
}
?>
--EXPECT--
