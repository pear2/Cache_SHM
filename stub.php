<?php
if (count(get_included_files()) > 1) {
    Phar::mapPhar();
    include_once 'phar://' . __FILE__ . DIRECTORY_SEPARATOR .
        '@PACKAGE_NAME@-@PACKAGE_VERSION@' . DIRECTORY_SEPARATOR . 'src'
        . DIRECTORY_SEPARATOR . 'PEAR2' . DIRECTORY_SEPARATOR . 'Autoload.php';
} else {
    $isHttp = isset($_SERVER['REQUEST_URI']);
    if ($isHttp) {
        header('Content-Type: text/plain;charset=UTF-8');
    }
    echo "@PACKAGE_NAME@ @PACKAGE_VERSION@\n";
    
    if (version_compare(phpversion(), '5.3.0', '<')) {
        echo "\nThis package requires PHP 5.3.0 or later.";
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
        } catch(Exception $e) {
            echo <<<HEREDOC
The PHAR extension is available, but was unable to read this PHAR file's hash.
Regardless, you should not be having any trouble using the package by directly
including the archive.

Exception details:
HEREDOC
                . $e . "\n\n";
        }
    } else {
        echo <<<HEREDOC
If you wish to use this package directly from this archive, you need to install
and enable the PHAR extension. Otherwise, you must instead extract this archive,
and include the autoloader.


HEREDOC;
    }
    
    if (in_array('apc', $available_extensions)) {
        if (version_compare(phpversion('apc'), '3.0.13', '>=')) {
            echo "A compatible APC version is available on this server.\n";
            if ($isHttp || 1 == ini_get('apc.enable_cli')) {
                echo "You should be able to use it under this SAPI (", PHP_SAPI,
                    ").\n";
            } else {
                echo "You can't use it under this SAPI (", PHP_SAPI, ").\n";
            }
            echo "\n";
        }
    }
    
    if (in_array('wincache', $available_extensions)) {
        if (version_compare(phpversion('wincache'), '1.1.0', '>=')) {
            echo "A compatible WinCache version is available on this server.\n";
            if ($isHttp) {
                echo "You should be able to use it under this SAPI (", PHP_SAPI,
                    ").\n";
            } else {
                echo "You can't use it under this SAPI (", PHP_SAPI, ").\n";
            }
            echo "\n";
        }
    }
    
    if ($isHttp) {
        if (empty($available_extensions)) {
            echo "You don't have any compatible extensions for this SAPI (",
                PHP_SAPI,
                ").\nInstall one of APC (>= 3.0.13) or WinCache (>= 1.1.0).";
        }
    } else {
        echo "You should be able to use the Placebo adapter under this SAPI (",
            PHP_SAPI, ").\n";
    }
}

__HALT_COMPILER();