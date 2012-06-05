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
namespace PEAR2\Cache\SHM;

/**
 * Required methods for adapters.
 * 
 * @category Cache
 * @package  PEAR2_Cache_SHM
 * @author   Vasil Rangelov <boen.robot@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link     http://pear2.php.net/PEAR2_Cache_SHM
 */
interface Adapter
{
    /**
     * Creates a new shared memory storage.
     * 
     * Estabilishes a separate persistent storage.
     * 
     * @param string $persistentId The ID for the storage. The storage will be
     * reused if it exists, or created if it doesn't exist. Data and locks are
     * namespaced by this ID.
     */
    public function __construct($persistentId);
    
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
    public function lock($key, $timeout = null);
    
    /**
     * Releases a named lock.
     * 
     * @param string $key Name of the key to release. Note that $key may
     * repeat for each distinct $persistentId.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function unlock($key);
    
    /**
     * Checks if a specified key is in the storage.
     * 
     * @param string $key Name of key to check.
     * 
     * @return bool TRUE if the key is in the storage, FALSE otherwise. 
     */
    public function exists($key);
    
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
    public function add($key, $value, $ttl = 0);
    
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
    public function set($key, $value, $ttl = 0);
    
    /**
     * Gets a value from the shared memory storage.
     * 
     * Gets the current value, or throws an exception if it's not stored.
     * 
     * @param string $key Name of key to get the value of.
     * 
     * @return mixed The current value of the specified key.
     */
    public function get($key);
    
    /**
     * Deletes a value from the shared memory storage.
     * 
     * @param string $key Name of key to delete.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function delete($key);
    
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
    public function inc($key, $step = 1);
    
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
    public function dec($key, $step = 1);

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
    public function cas($key, $old, $new);
    
    /**
     * Clears the persistent storage.
     * 
     * Clears the persistent storage, i.e. removes all keys. Locks are left
     * intact.
     * 
     * @return void
     */
    public function clear();
    
    /**
     * Retrieve an external iterator
     * 
     * Returns an external iterator.
     * 
     * @param string $filter   A PCRE regular expression. Only matching keys
     * will be iterated over. Setting this to NULL matches all keys of this
     * instance.
     * @param bool   $keysOnly Whether to return only the keys, or return both
     * the keys and values.
     * 
     * @return An array or instance of an object implementing {@link \Iterator}
     * or {@link \Traversable}.
     */
    public function getIterator($filter = null, $keysOnly = false);
}