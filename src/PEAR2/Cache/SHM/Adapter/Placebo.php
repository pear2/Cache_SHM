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
 * This adapter is not truly persistent. It is intended to emulate persistency
 * in non persistent environments, so that upper level applications can use a
 * single code path for persistent and non persistent code.
 * 
 * @category Cache
 * @package  PEAR2_Cache_SHM
 * @author   Vasil Rangelov <boen.robot@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link     http://pear2.php.net/PEAR2_Cache_SHM
 */
class Placebo implements Adapter
{
    /**
     * @var string ID of the current storage. 
     */
    protected $persistentId;
    
    /**
     * The data storage.
     * 
     * Each persistent ID is a key, and the value is an array.
     * Each such array has data keys as its keys, and an array as a value.
     * Each such array has as its elements the value, the timeout and the time
     * the data was set.
     * @var array 
     */
    protected static $data = array();
    
    /**
     * @var array Array of lock names (as values) for each persistent ID (as
     * key) obtained during the current request.
     */
    protected static $locksBackup = array();
    
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
        static::$data[$persistentId] = array();
        static::$locksBackup[$persistentId] = array();
        $this->persistentId = $persistentId;
    }
    
    /**
     * Pretends to obtain a lock.
     * 
     * @param string $key     Ignored.
     * @param double $timeout Ignored.
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function lock($key, $timeout = null)
    {
        $key = (string) $key;
        if (in_array($key, static::$locksBackup[$this->persistentId], true)) {
            return false;
        }
        static::$locksBackup[$this->persistentId][] = $key;
        return true;
    }
    
    /**
     * Pretends to release a lock.
     * 
     * @param string $key Ignored
     * 
     * @return bool TRUE on success, FALSE on failure.
     */
    public function unlock($key)
    {
        $key = (string) $key;
        if (in_array($key, static::$locksBackup[$this->persistentId], true)) {
            return false;
        }
        unset(static::$locksBackup[$this->persistentId][array_search(
            $key, static::$locksBackup[$this->persistentId], true
        )]);
        return true;
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
        if (array_key_exists($key, static::$data[$this->persistentId])) {
            $item = static::$data[$this->persistentId][$key];
            if (($item[2] - time()) < $item[1]) {
                return true;
            }
            unset(static::$data[$this->persistentId][$key]);
        }
        return false;
    }
    
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
    public function add($key, $value, $ttl = 0)
    {
        if ($this->exists($key)) {
            return false;
        }
        return $this->set($key, $value, $ttl);
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
        static::$data[$this->persistentId][$key] = array(
            $value, $ttl, time()
        );
        return true;
    }
    
    /**
     * Gets a value from the shared memory storage.
     * 
     * Gets the current value, or throws an exception if it's not stored.
     * 
     * @param string $key Name of key to get the value of.
     * 
     * @return mixed The current value of the specified key.
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            return static::$data[$this->persistentId][$key][0];
        }
        throw new SHM\InvalidArgumentException(
            'Unable to fetch key. No such key, or key has expired.', 200
        );
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
        if ($this->exists($key)) {
            unset(static::$data[$this->persistentId][$key]);
            return true;
        }
        return false;
    }
}