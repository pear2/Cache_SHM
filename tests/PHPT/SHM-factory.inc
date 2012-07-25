<?php
namespace PEAR2\Cache;
require_once '_runner.inc';

$object = SHM::factory('TEST');
\assertSame(true, $object instanceof SHM, __FILE__);