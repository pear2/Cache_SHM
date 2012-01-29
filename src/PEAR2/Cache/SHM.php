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
class SHM
{
    protected $adapter;
    
    /**
     * Creates a new shared memory storage.
     * 
     * Estabilishes a separate persistent storage.
     * 
     * @param string|Adapter $persistentId The ID for the storage or an
     * already instanciated storage adapter. If an ID is specified, an adapter
     * will automatically be chosen based on the available extensions.
     */
    public function __construct($persistentId)
    {
        if ($persistentId instanceof Adapter) {
            $this->adapter = $persistentId;
        } else {
            if ('cli' === PHP_SAPI) {
                $this->adapter = new Adapter\Placebo($persistentId);
            } elseif (version_compare(phpversion('wincache'), '1.1.0', '>=')) {
                $this->adapter = new Adapter\Wincache($persistentId);
            } elseif (version_compare(phpversion('apc'), '3.0.13', '>=')) {
                $this->adapter = new Adapter\APC($persistentId);
            } else {
                throw new SHM\InvalidArgumentException(
                    'No appropriate adapter available', 1
                );
            }
        }
    }
    
    /**
     * Get the currently set SHM adapter.
     * 
     * @return Adapter The currently set adapter 
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    /**
     * Calls a method from the adapter.
     * 
     * This is a magic method, thanks to which any method you call will be
     * redirected to the adapter. Every adapter implements at minimum the
     * {@link Adapter} interface, so check it out for what you can expect as
     * common functionality.
     * 
     * @param string $method The adapter method to call/
     * @param array  $args   The arguments to the method.
     * 
     * @return mixed Whatever the adapter method returns.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(
            array($this->adapter, $method), $args
        );
    }
}