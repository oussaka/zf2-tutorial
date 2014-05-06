<?php

/**
 * This autoloading setup is really more complicated than it needs to be for most
 * applications. The added complexity is simply to reduce the time it takes for
 * new developers to be productive with a fresh skeleton. It allows autoloading
 * to be correctly configured, regardless of the installation method and keeps
 * the use of composer completely optional. This setup should work fine for
 * most users, however, feel free to configure autoloading however you'd like.
 */

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

// Support for ZF2_PATH environment variable or git submodule
if ($zf2Path = getenv('ZF2_PATH') ?: (is_dir('vendor/ZF2/library') ? 'vendor/ZF2/library' : false)) {
    if (isset($loader)) {
        $loader->add('Zend', $zf2Path . '/Zend');
        // Using Zend Framework 1 libraries
        //we still need set include path because some component still use require_once others
        // $loader->setUseIncludePath($Zf1Path);
        // $loader->add('Zend_', $Zf1Path.'/Zend');
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                // another way to use Zend 1
                'prefixes' => array(
                	// 'Zend_'     => $Zf1Path.'/Zend'
                	// then use this in controller, for test : $date = new \Zend_Date; print_r($date->toArray());
                ),
            )
        ));
    }
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
