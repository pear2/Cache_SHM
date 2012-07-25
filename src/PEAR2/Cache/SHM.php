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

/**
 * Calls adapters. 
 */
use PEAR2\Cache\SHM\Adapter;

/**
 * Main class for this package.
 * 
 * Automatically chooses an adapter based on the available extensions.
 * 
 * @category Cache
 * @package  PEAR2_Cache_SHM
 * @author   Vasil Rangelov <boen.robot@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link     http://pear2.php.net/PEAR2_Cache_SHM
 */
abstract class SHM implements \IteratorAggregate
{
    
    /**
     * Creates a new shared memory storage.
     * 
     * Estabilishes a separate persistent storage. Adapter is automatically
     * chosen based on the available extensions.
     * 
     * @param string $persistentId The ID for the storage.
     * 
     * @return self|SHM A new instance of an SHM adapter (child of this class).
     */
    public static function factory($persistentId)
    {
        if ('cli' === PHP_SAPI) {
            return new Adapter\Placebo($persistentId);
        } elseif (version_compare(phpversion('wincache'), '1.1.0', '>=')) {
            return new Adapter\Wincache($persistentId);
        } elseif (version_compare(phpversion('apc'), '3.0.13', '>=')) {
            return new Adapter\APC($persistentId);
        } else {
            throw new SHM\InvalidArgumentException(
                'No appropriate adapter available', 1
            );
        }
    }
    
    /**
     * Gets a value from the shared memory storage.
     * 
     * This is a magic method, thanks to which any property you attempt to get
     * the value of will be fetched from the adapter, treating the property name
     * as the key of the value to get.
     * 
     * @param string $key Name of key to get.
     * 
     * @return mixed The current value of the specified key.
     */
    public function __get($key)
    {
        return $this->get($key);
    }
    
    /**
     * Sets a value in the shared memory storage.
     * 
     * This is a magic method, thanks to which any property you attempt to set
     * the value of will be set by the adapter, treating the property name as
     * the key of the value to set. The value is set without a TTL.
     * 
     * @param string $key   Name of key to associate the value with.
     * @param mixed  $value Value for the specified key.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }
    
    /**
     * Checks if a specified key is in the storage.
     * 
     * This is a magic method, thanks to which any property you call isset() on
     * will be checked by the adapter, treating the property name as the key
     * of the value to check.
     * 
     * @param string $key Name of key to check.
     * 
     * @return bool TRUE if the key is in the storage, FALSE otherwise. 
     */
    public function __isset($key)
    {
        return $this->exists($key);
    }
    
    /**
     * Deletes a value from the shared memory storage.
     * 
     * This is a magic method, thanks to which any property you attempt to unset
     * the value of will be unset by the adapter, treating the property name as
     * the key of the value to delete.
     * 
     * @param string $key Name of key to delete.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function __unset($key)
    {
        return $this->delete($key);
    }
    
    /**
     * Creates a new shared memory storage.
     * 
     * Estabilishes a separate persistent storage.
     * 
     * @param string $persistentId The ID for the storage. The storage will be
     * reused if it exists, or created if it doesn't exist. Data and locks are
     * namespaced by this ID.
     */
    abstract public function __construct($persistentId);
    
    /**
     * Obtains a named lock.
     * 
     * @param string $key     Name of the key to obtain. Note that $key may
     * repeat for each distinct $persistentId.
     * @param double $timeout If the lock can't be immediatly obtained, the
     * script will block for at most the specified amount of seconds. Setting
     * this to 0 makes lock obtaining non blocking, and setting it to NULL makes
     * it block without a time limit.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    abstract public function lock($key, $timeout = null);
    
    /**
     * Releases a named lock.
     * 
     * @param string $key Name of the key to release. Note that $key may
     * repeat for each distinct $persistentId.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    abstract public function unlock($key);
    
    /**
     * Checks if a specified key is in the storage.
     * 
     * @param string $key Name of key to check.
     * 
     * @return bool TRUE if the key is in the storage, FALSE otherwise. 
     */
    abstract public function exists($key);
    
    /**
     * Adds a value to the shared memory storage.
     * 
     * Adds a value to the storage if it doesn't exist, or fails if it does.
     * 
     * @param string $key   Name of key to associate the value with.
     * @param mixed  $value Value for the specified key.
     * @param int    $ttl   Seconds to store the value. If set to 0 indicates no
     * time limit.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    abstract public function add($key, $value, $ttl = 0);
    
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
    abstract public function set($key, $value, $ttl = 0);
    
    /**
     * Gets a value from the shared memory storage.
     * 
     * Gets the current value, or throws an exception if it's not stored.
     * 
     * @param string $key Name of key to get the value of.
     * 
     * @return mixed The current value of the specified key.
     */
    abstract public function get($key);
    
    /**
     * Deletes a value from the shared memory storage.
     * 
     * @param string $key Name of key to delete.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    abstract public function delete($key);
    
    /**
     * Increases a value from the shared memory storage.
     * 
     * Increases a value from the shared memory storage. Unlike a plain
     * set($key, get($key)+$step) combination, this function also implicitly
     * performs locking.
     * 
     * @param string $key  Name of key to increase.
     * @param int    $step Value to increase the key by.
     * 
     * @return int The new value.
     */
    abstract public function inc($key, $step = 1);
    
    /**
     * Decreases a value from the shared memory storage.
     * 
     * Decreases a value from the shared memory storage. Unlike a plain
     * set($key, get($key)-$step) combination, this function also implicitly
     * performs locking.
     * 
     * @param string $key  Name of key to decrease.
     * @param int    $step Value to decrease the key by.
     * 
     * @return int The new value.
     */
    abstract public function dec($key, $step = 1);

    /**
     * Sets a new value if a key has a certain value.
     * 
     * Sets a new value if a key has a certain value. This function only works
     * when $old and $new are longs.
     * 
     * @param string $key Key of the value to compare and set.
     * @param int    $old The value to compare the key against.
     * @param int    $new The value to set the key to.
     * 
     * @return bool TRUE on success, FALSE on failure. 
     */
    abstract public function cas($key, $old, $new);
    
    /**
     * Clears the persistent storage.
     * 
     * Clears the persistent storage, i.e. removes all keys. Locks are left
     * intact.
     * 
     * @return void
     */
    abstract public function clear();
}