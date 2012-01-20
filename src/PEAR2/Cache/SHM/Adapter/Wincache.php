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

namespace PEAR2\Cache\SHM\Adapter;

/**
 * Implements the adapter interface. 
 */
use PEAR2\Cache\SHM\Adapter;

class Wincache implements Adapter {

    protected $persistentId;
    public function __construct($persistentId)
    {
        $this->persistentId = str_replace('\\', '/', $persistentId);
    }

    public function __destruct()
    {
        
    }

    public function lock($key, $timeout = null)
    {
        return wincache_lock($this->persistentId . ' ' . $key);
    }

    public function unlock($key)
    {
        return wincache_unlock($this->persistentId . ' ' . $key);
    }

}