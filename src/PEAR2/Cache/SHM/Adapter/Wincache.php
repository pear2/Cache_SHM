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

class Wincache implements Adapter
{

    protected $persistentId;
    public function __construct($persistentId)
    {
        if (strpos($persistentId, '\\') !== false) {
            throw new SHM\InvalidArgumentException(
                '$persistentId must not contain "\"', 200
            );
        }
        $this->persistentId = $persistentId;
    }

    public function lock($key, $timeout = null)
    {
        if (strpos($key, '\\') !== false) {
            throw new SHM\InvalidArgumentException(
                '$key must not contain "\"', 201
            );
        }
        return wincache_lock($this->persistentId . ' ' .$key);
    }

    public function unlock($key)
    {
        return wincache_unlock($this->persistentId . ' ' . $key);
    }

    public function add($key, $value, $ttl = 0)
    {
        return wincache_add($this->persistentId . ' ' . $key, $value, $ttl);
    }

    public function set($key, $value, $ttl = 0)
    {
        return wincache_set($this->persistentId . ' ' . $key, $value, $ttl);
    }

    public function delete($key)
    {
        return wincache_delete($this->persistentId . ' ' . $key);
    }

    public function get($key)
    {
        return wincache_get($this->persistentId . ' ' . $key);
    }

}