--TEST--
Placebo tests.
--DESCRIPTION--
Sets up the settings for the Placebo adapter, and executes its tests.
--REDIRECTTEST--
return array(
    'TESTS' => getcwd() . DIRECTORY_SEPARATOR . 'Common'
);
