--TEST--
APC tests.
--DESCRIPTION--
Sets up the settings for the APC adapter, and executes its tests.
--CGI--
--GET--

--REDIRECTTEST--
return array(
    'GET' => array('adapter' => 'apc', 'nokeycode' => '101'),
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);
