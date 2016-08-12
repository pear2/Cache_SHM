--TEST--
APC tests.
--DESCRIPTION--
Sets up the settings for the APC adapter, and executes its tests.
--SKIPIF--
<?php if (!version_compare(phpversion('apc'), '3.1.1', '>=')) {
    die('Skip APC 3.1.1 or greather is required.');
}
?>
--CGI--
--GET--

--REDIRECTTEST--
return array(
    'GET' => array('adapter' => 'APC', 'nokeycode' => '101'),
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);