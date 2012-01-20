<?php
namespace PEAR2\Cache;

class ClientFeaturesTest extends \PHPUnit_Framework_TestCase
{
    
    public function testConsruction()
    {
        $persistentId = 'TEST';
        $object1 = new SHM(new SHM\Adapter\APC($persistentId));
        $object2 = new SHM(new SHM\Adapter\APC($persistentId));
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object1);
        $this->assertInstanceOf('\PEAR2\Cache\SHM', $object2);
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\APC', $object1->getAdapter()
        );
        $this->assertInstanceOf(
            '\PEAR2\Cache\SHM\Adapter\APC', $object2->getAdapter()
        );
    }
        
}