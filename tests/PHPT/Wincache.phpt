--TEST--
Wincache tests.
--DESCRIPTION--
Sets up the settings for the Wincache adapter, and executes its tests.
--SKIPIF--
<?php if (!version_compare(phpversion('wincache'), '1.1.0', '>=')) {
    die('Skip WinCache 1.1.0 or greather is required.');
?>
--CGI--
--GET--

--REDIRECTTEST--
return array(
    'GET' => array('adapter' => 'Wincache', 'nokeycode' => '300'),
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);