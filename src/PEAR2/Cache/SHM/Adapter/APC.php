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

class APC implements Adapter
{
    /**
     * @var string ID of the current storage. 
     */
    protected $persistentId;
    
    /**
     * List of persistent IDs.
     * 
     * A list of persistent IDs within the current request with a boolean
     * indicating whether their locks can be destroyed. Used as an attempt to
     * ensure implicit lock releases even on errors in the critical sections,
     * since APC doesn't have an actual locking function.
     * @var array 
     */
    protected static $requestInstances = array();
    
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
        $this->persistentId = __NAMESPACE__ . '\APC ' . $persistentId;
        if (isset(static::$destructables[$this->persistentId])) {
            static::$requestInstances[$this->persistentId]++;
        } else {
            static::$requestInstances[$this->persistentId] = 1;
        }
        register_shutdown_function(
            __CLASS__ . '::destroyLocks', $this->persistentId
        );
    }
    
    /**
     * Destroys locks in a storage.
     * 
     * This function is not meant to be used directly. It is implicitly called
     * by the the destructor and as a shutdown function when the request ends.
     * One of these calls ends up destroying any unreleased locks obtained
     * during the request.
     * 
     * @param string $internalPersistentId The internal persistent ID being
     * destroyed.
     * 
     * @return void
     * @internal
     */
    public static function destroyLocks($internalPersistentId)
    {
        if (0 !== static::$requestInstances[$internalPersistentId]) {
            //Called from a shutdown function. Locks not released normally.
            foreach (static::$locksBackup as $lock) {
                apc_delete($lock);
            }
        }
    }
    
    /**
     * Destroys any locks set by this instance, or the storage itself if
     * necessary.
     */
    public function __destruct()
    {
        static::$requestInstances[$this->persistentId]--;
        static::destroyLocks($this->persistentId);
    }
    
    
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
    public function lock($key, $timeout = null)
    {
        $lock = $this->persistentId . 'locks ' . $key;
        $start = microtime(true);
        while (!apc_add($lock, 1)) {
            if ($timeout !== null && (microtime(true) - $start) > $timeout) {
                return false;
            }
        }
        static::$locksBackup[] = $lock;
        return true;
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
        $lock = $this->persistentId . 'locks ' . $key;
        $success = apc_delete($lock);
        if ($success) {
            unset(static::$locksBackup[array_search(
                $lock, static::$locksBackup, true
            )]);
            return true;
        }
        return false;
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
        return apc_exists($this->persistentId . 'values ' . $key);
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
        return apc_add($this->persistentId . 'values ' . $key, $value, $ttl);
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
        return apc_store($this->persistentId . 'values ' . $key, $value, $ttl);
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
        $fullKey = $this->persistentId . 'values ' . $key;
        if (apc_exists($fullKey)) {
            $value = apc_fetch($fullKey, $success);
            if (!$success) {
                throw new SHM\InvalidArgumentException(
                    'Unable to fetch key. ' .
                    'Key has either just now expired or (if no TTL was set) ' .
                    'is possibly in a race condition with another request.', 100
                );
            }
            return $value;
        }
        throw new SHM\InvalidArgumentException('No such key in cache', 101);
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
        return apc_delete($this->persistentId . 'values ' . $key);
    }
}