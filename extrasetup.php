<?php

/**
 * extrasetup.php for PEAR2_Cache_SHM.
 * 
 * PHP version 5.3
 * 
 * @category  Caching
 * @package   PEAR2_Cache_SHM
 * @author    Vasil Rangelov <boen.robot@gmail.com>
 * @copyright 2011 Vasil Rangelov
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   GIT: $Id$
 * @link      http://pear2.php.net/PEAR2_Cache_SHM
 */

$packages = array(
    'pear2.php.net' => array(
        'PEAR2_Autoload'
    )
);

$extrafiles = array();
$config = Pyrus\Config::current();
$registry = $config->registry;
$phpDir = $config->php_dir;

foreach ($packages as $channel => $channelPackages) {
    foreach ($channelPackages as $package) {
        foreach ($registry->toPackage($package, $channel)->installcontents
            as $file => $info) {
            if (strpos($file, 'php/') === 0) {
                $filename = substr($file, 4);
                $extrafiles['src/' . $filename]
                    = realpath($phpDir . DIRECTORY_SEPARATOR . $filename);
            }
        }
    }
}