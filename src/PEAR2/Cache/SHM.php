<?php

/**
 * ~~summary~~
 * 
 * ~~description~~
 * 
 * PHP version 5
 * 
 * @category  Cache
 * @package   PEAR2_Cache_SHM
 * @author    Vasil Rangelov <boen.robot@gmail.com>
 * @copyright 2011 Vasil Rangelov
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   GIT: $Id$
 * @link      http://pear2.php.net/PEAR2_Cache_SHM
 */
/**
 * The namespace declaration.
 */
namespace PEAR2\Cache;

class SHM
{
    protected $adapter;
    public function __construct($persistendId)
    {
        if ($persistendId instanceof SHM\Adapter) {
            $this->adapter = $persistendId;
        } else {
            if (version_compare(phpversion('apc'), '3.0.13', '>=')) {
                $this->adapter = new SHM\Adapter\APC($persistendId);
            } elseif (version_compare(phpversion('wincache'), '1.1.0', '>=')) {
                $this->adapter = new SHM\Adapter\Wincache($persistendId);
            } else {
                throw new \InvalidArgumentException(
                    'No appropriate adapter available', 1
                );
            }
        }
    }
    
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    public function __call($method, $args)
    {
        return call_user_func_array(
            array($this->adapter, $method), $args
        );
    }
}