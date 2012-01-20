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

interface Adapter
{
    public function __construct($persistentId);
    public function lock($key, $timeout = null);
    public function unlock($key);
    public function add($key, $value, $ttl = 0);
    public function set($key, $value, $ttl = 0);
    public function get($key);
    public function delete($key);
}