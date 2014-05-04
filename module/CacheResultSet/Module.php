<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CacheResultSet;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }


    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'CacheResultSet\Model\AlbumTable' =>  function($sm){
    						$dbAdapter    = $sm->get('Zend\Db\Adapter\Adapter');
    						$cacheAdapter = $sm->get('Zend\Cache\Storage\Filesystem');

    						$table     = new Model\AlbumTable($dbAdapter);
    						$table->setCache($cacheAdapter);

    						return $table;
    					},
    					'Zend\Cache\Storage\Filesystem' => function($sm){
    						$cache = \Zend\Cache\StorageFactory::factory(array(
								'adapter' => array(
									'name' => 'filesystem',
									'options' => array(
											'cache_dir' => './data/cache'
									),
								),
								'plugins' => array(
								    // Don't throw exceptions on cache errors
									'exception_handler' => array('throw_exceptions' => true),
				                    'serializer'
					            )
    					   ));
    					   return $cache;
    					},
    			),
    	);
    }
}
