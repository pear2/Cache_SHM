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

/**
 * Throws exceptions from this namespace. 
 */
use PEAR2\Cache\SHM;

/**
 * Shared memory adapter for the WinCache extension.
 * 
 * @category Cache
 * @package  PEAR2_Cache_SHM
 * @author   Vasil Rangelov <boen.robot@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link     http://pear2.php.net/PEAR2_Cache_SHM
 */
class Wincache implements Adapter
{
    /**
     * @var string ID of the current storage. 
     */
    protected $persistentId;
    
    /**
     * Creates a new shared memory storage.
     * 
     * Estabilishes a separate persistent storage.
     * 
     * @param string $persistentId The ID for the storage. The storage will be
     * reused if it exists, or created if it doesn't exist. Data and locks are
     * namespaced by this ID.
     */
    public function __construct($persistentId)
    {
        if (strpos($persistentId, '\\') !== false) {
            throw new SHM\InvalidArgumentException(
                '$persistentId must not contain "\"', 200
            );
        }
        $this->persistentId = $persistentId . ' ';
    }

    
    /**
     * Obtains a named lock.
     * 
     * @param string $key     Name of the key to obtain. Note that $key may
     * repeat for each distinct $persistentId.
     * @param double $timeout Ignored with WinCache. Script will always block if
     * the lock can't be immediatly obtained.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function lock($key, $timeout = null)
    {
        if (strpos($key, '\\') !== false) {
            throw new SHM\InvalidArgumentException(
                '$key must not contain "\"', 201
            );
        }
        return wincache_lock($this->persistentId . $key);
    }
    
    /**
     * Releases a named lock.
     * 
     * @param string $key Name of the key to release. Note that $key may
     * repeat for each distinct $persistentId.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function unlock($key)
    {
        return wincache_unlock($this->persistentId . $key);
    }
    
    /**
     * Checks if a specified key is in the storage.
     * 
     * @param string $key Name of key to check.
     * 
     * @return bool TRUE if the key is in the storage, FALSE otherwise. 
     */
    public function exists($key)
    {
        return wincache_exists($this->persistentId . $key);
    }
    
    /**
     * Adds a value to the shared memory storage.
     * 
     * Sets a value to the storage if it doesn't exist, or fails if it does.
     * 
     * @param string $key   Name of key to associate the value with.
     * @param mixed  $value Value for the specified key.
     * @param int    $ttl   Seconds to store the value. If set to 0 indicates no
     * time limit.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function add($key, $value, $ttl = 0)
    {
        return wincache_add($this->persistentId . $key, $value, $ttl);
    }
    
    /**
     * Sets a value in the shared memory storage.
     * 
     * Adds a value to the storage if it doesn't exist, overwrites it otherwise.
     * 
     * @param string $key   Name of key to associate the value with.
     * @param mixed  $value Value for the specified key.
     * @param int    $ttl   Seconds to store the value. If set to 0 indicates no
     * time limit.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function set($key, $value, $ttl = 0)
    {
        return wincache_set($this->persistentId . $key, $value, $ttl);
    }
    
    /**
     * Gets a value from the shared memory storage.
     * 
     * Gets the current value, or throws an exception if it's not stored.
     * 
     * @param string $key Name of key to get the value of.
     * 
     * @return The current value of the specified key.
     */
    public function get($key)
    {
        return wincache_get($this->persistentId . $key);
    }
    
    /**
     * Deletes a value from the shared memory storage.
     * 
     * @param string $key Name of key to delete.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function delete($key)
    {
        return wincache_delete($this->persistentId . $key);
    }

}