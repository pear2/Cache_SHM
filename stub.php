<?php

/**
 * stub for PEAR2_Cache_SHM.
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

if (count(get_included_files()) > 1) {
    Phar::mapPhar();
    include_once 'phar://' . __FILE__ . DIRECTORY_SEPARATOR .
        '@PACKAGE_NAME@-@PACKAGE_VERSION@' . DIRECTORY_SEPARATOR . 'src'
        . DIRECTORY_SEPARATOR . 'PEAR2' . DIRECTORY_SEPARATOR . 'Autoload.php';
    return;
}

$isHttp = isset($_SERVER['REQUEST_URI']);
if ($isHttp) {
    header('Content-Type: text/plain;charset=UTF-8');
}
echo "@PACKAGE_NAME@ @PACKAGE_VERSION@\n";

if (version_compare(phpversion(), '5.3.0', '<')) {
    echo "\nERROR: This package requires PHP 5.3.0 or later.\n";
    exit(1);
}

$available_extensions = array();
foreach (array('apc', 'wincache') as $ext) {
    if (extension_loaded($ext)) {
        $available_extensions[] = $ext;
    }
}

if (extension_loaded('phar')) {
    try {
        $phar = new Phar(__FILE__);
        $sig = $phar->getSignature();
        echo "{$sig['hash_type']} hash: {$sig['hash']}\n\n";
    } catch (Exception $e) {
        echo <<<HEREDOC

The PHAR extension is available, but was unable to read this PHAR file's hash.
HEREDOC;
        if (false !== strpos($e->getMessage(), 'file extension')) {
            echo <<<HEREDOC

This can happen if you've renamed the file to ".php" instead of ".phar".
Regardless, you should be able to include this file without problems.
HEREDOC;
        }
    }
} else {
    echo <<<HEREDOC
WARNING: If you wish to use this package directly from this archive, you need
         to install and enable the PHAR extension. Otherwise, you must instead
         extract this archive, and include the autoloader.

HEREDOC;
}

if (in_array('apc', $available_extensions)) {
    if (version_compare(phpversion('apc'), '3.0.13', '>=')) {
        echo <<<HEREDOC
A compatible APC version is available on this server.
HEREDOC;
        if (ini_get('apc.enabled')) {
            if ($isHttp || ini_get('apc.enable_cli')) {
                echo "You should be able to use it under this SAPI (", PHP_SAPI,
                    ").\n";
            } else {
                echo "\nWARNING: You can't use it under this SAPI (", PHP_SAPI,
                    ").\n";
            }
            echo "\n";
        } else {
            echo <<<HEREDOC
WARNING: Although present, the APC extension is disabled via the apc.enabled
         INI setting, making this package unusable with it.
         You need to enable it from php.ini.

HEREDOC;
        }
    }
}

if (in_array('wincache', $available_extensions)) {
    if (version_compare(phpversion('wincache'), '1.1.0', '>=')) {
        echo <<<HEREDOC
A compatible WinCache version is available on this server.
HEREDOC;
        if (ini_get('wincache.ucenabled')) {
            if ($isHttp || ini_get('wincache.enablecli')) {
                echo "You should be able to use it under this SAPI (", PHP_SAPI,
                    ").\n";
            } else {
                echo "\nWARNING: You can't use it under this SAPI (", PHP_SAPI,
                    ").\n";
            }
            echo "\n";
        } else {
            echo <<<HEREDOC
WARNING: The user cache of the WinCache is disabled via the wincache.ucenabled
         INI setting, making this package unusable with it.
         You need to enable it from php.ini.

HEREDOC;
        }
    }
}

if ($isHttp) {
    if (empty($available_extensions)) {
        echo <<<HEREDOC
WARNING: You don't have any compatible extensions for this SAPI.
         Install one of APC (>= 3.0.13) or WinCache (>= 1.1.0).
HEREDOC;
        echo '         (The current SAPI is "', PHP_SAPI, ").\n\n";
    }
} else {
    echo "You should be able to use the Placebo adapter under this SAPI (",
        PHP_SAPI, ").\n\n";
}

__HALT_COMPILER();