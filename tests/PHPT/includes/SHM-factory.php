<?php
use PEAR2\Cache\SHM;
require_once 'runner.php';

$object = SHM::factory('TEST');

assertSame(true, $object instanceof SHM, __FILE__);