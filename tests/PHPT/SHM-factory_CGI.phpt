--TEST--
Factory CGI test.
--DESCRIPTION--
Checks that SHM::factory() can be called from CGI.
--CGI--
--GET--

--FILE_EXTERNAL--
includes/SHM-factory.php
--EXPECT--
