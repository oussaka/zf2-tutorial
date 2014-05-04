<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$dbParams = array(
		'database'  => 'zf-tutorial',
		'username'  => 'root',
		'password'  => '',
		'hostname'  => 'localhost'
);

return array(
    'db' => array(
        'driver' 		=> 'Pdo',
        'dsn'           => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
		'database'  	=> $dbParams['database'],
		'username'		=> $dbParams['username'],
		'password'  	=> $dbParams['password'],
		'hostname'  	=> $dbParams['hostname'],
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
	'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            // Using Zend\Cache and HydratingResultSet to Save Database Resultset into Cache
            /* 'Zend\Cache\Storage\Filesystem' => function($sm){
            	$cache = \Zend\Cache\StorageFactory::factory(array(
            			'adapter' => 'filesystem',
            			'plugins' => array(
            			        // Don't throw exceptions on cache errors
            					'exception_handler' => array('throw_exceptions' => false),
            					'serializer'
            			)
            	));

            	$cache->setOptions(array(
            			'cache_dir' => './data/cache'
            	));

            	return $cache;
            }, */
        ),
    ),
);
