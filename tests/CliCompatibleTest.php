<?php
namespace PEAR2\Cache;

class CliCompatibleTest extends \PHPUnit_Framework_TestCase
{
    
    public function testApcConsruction()
    {
        $persistentId = 'TEST';
        $object1 = new SHM(new SHM\Adapter\APC($persistentId));
        $object2 = new SHM(new SHM\Adapter\APC($persistentId));
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object1);
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object2);
        
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\APC',
            $object1->getAdapter()
        );
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\APC',
            $object2->getAdapter()
        );
    }
    
    public function testWincacheConsruction()
    {
        $persistentId = 'TEST';
        $object1 = new SHM(new SHM\Adapter\Wincache($persistentId));
        $object2 = new SHM(new SHM\Adapter\Wincache($persistentId));
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object1);
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object2);
        
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\Wincache',
            $object1->getAdapter()
        );
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\Wincache',
            $object2->getAdapter()
        );
    }
    
    public function testWincacheConsructionWithSpecialName()
    {
        $persistentId = 'TEST \ THIS %\% and more';
        $object1 = new SHM(new SHM\Adapter\Wincache($persistentId));
        $object2 = new SHM(new SHM\Adapter\Wincache($persistentId));
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object1);
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object2);
        
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\Wincache',
            $object1->getAdapter()
        );
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\Wincache',
            $object2->getAdapter()
        );
    }
}
