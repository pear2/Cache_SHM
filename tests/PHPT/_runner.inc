<?php
require_once 'PEAR2/Autoload.php';
\PEAR2\Autoload::initialize(
    realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../src')
);

function assertSame($expected, $actual, $file) {
    if ($expected !== $actual) {
        echo "Failed asserting that ";
        var_dump($actual);
        echo " is ";
        var_dump($expected);
        echo ' in "' . $file . "\"\n\n";
    }
}

foreach (new RecursiveDirectoryIterator(
        __DIR__ . DIRECTORY_SEPARATOR . 'Common',
        FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME
    ) as $file) {
    try {
        require_once $file;
    }catch (Exception $e) {
        echo $e;
    }
}