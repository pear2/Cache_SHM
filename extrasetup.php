<?php
$extrafiles = array();

foreach (
    array(
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'PEAR2_Autoload.tgz'
    ) as $packageRoot
) {
    $pkg = new \Pyrus\Package(
        $packageRoot
    );
    foreach ($pkg->files as $filename => $info) {
        if (0 === strpos($filename, 'tests/')
            || 0 === strpos($filename, 'test/')
            || 0 === strpos($filename, 'docs/')
            || 0 === strpos($filename, 'doc/')
        ) {
            unset($pkg->files[$filename]);
        }
        
        if (0 === strpos($filename, 'php/')) {
//            $newFileName = 'src/' . substr($filename, strlen('php/'));
//            $info['name'] = $newFileName;
//            $pkg->files[$newFileName] = $info;
//            unset($pkg->files[$filename]);
        }
    }
    $extrafiles[] = $pkg;
}