<?php
use PEAR2\Autoload;

require_once 'PEAR2/Autoload.php';

$cwd = getcwd();
chdir(__DIR__ . '/../..');
Autoload::initialize(realpath(realpath('../src')));
chdir($cwd);

function assertSame($expected, $actual, $file) {
    if ($expected !== $actual) {
        echo "Failed asserting that ";
        var_dump($actual);
        echo " is ";
        var_dump($expected);
        echo ' in "' . $file . "\"\n\n";
    }
}

$adapter = isset($_GET['adapter']) ? $_GET['adapter'] : 'Placebo';