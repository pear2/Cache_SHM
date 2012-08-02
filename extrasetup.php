<?php
$extrafiles = array();
$phpDir = Pyrus\Config::current()->php_dir . DIRECTORY_SEPARATOR;

$PEAR2_Autoload_Path = 'PEAR2/Autoload.php';
$extrafiles = array(
    'src/' . $PEAR2_Autoload_Path => $phpDir . $PEAR2_Autoload_Path
);