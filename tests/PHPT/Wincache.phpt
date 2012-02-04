--TEST--
Wincache tests.
--DESCRIPTION--
Sets up the settings for the Wincache adapter, and executes its tests.
--CGI--
--GET--

--REDIRECTTEST--
return array(
    'GET' => array('adapter' => 'wincache', 'nokeycode' => '300'),
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);
