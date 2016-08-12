--TEST--
APCu tests.
--DESCRIPTION--
Sets up the settings for the APCu adapter, and executes its tests.
--SKIPIF--
<?php if (!version_compare(phpversion('apcu'), '5.0.0', '>=')) {
    die('Skip APCu 5.0.0 or greather is required.');
}
?>
--CGI--
--GET--

--REDIRECTTEST--
return array(
    'GET' => array('adapter' => 'APCu', 'nokeycode' => '101'),
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);