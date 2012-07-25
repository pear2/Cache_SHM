--TEST--
Factory CGI test.
--DESCRIPTION--
Checks that SHM::factory() can be called from the command line.
--CGI--
--GET--

--FILE_EXTERNAL--
SHM-factory.inc
--XFAIL--
Odd startup PCNTL related errors coming from APC...
just for this test for some reason.
--EXPECT--
