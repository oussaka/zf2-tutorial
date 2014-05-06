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
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        /* @var $eventManager \Zend\EventManager\EventManager */
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach('dispatch', array($this, 'loadConfigurationDispath' ));
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
    }

    /**
     * Pour afficher le nom du module dans le layout
     *
     * @param MvcEvent $e
     */
    public function loadConfigurationDispath(MvcEvent $e)
    {

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);

        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        //set 'variable' into layout...
        $controller->layout()->modulenamespace = $moduleNamespace;
        // You can setting variable using this too : $e->getViewModel()->setVariable('modulenamespace', $moduleNamespace);

        /*
         And in the layout, You can call :
         # Current Module Namespace is <?php echo $this->modulenamespace; ?>
         How about call the variable for view, this is it :
         # Current Module Namespace is <?php echo $this->layout()->modulenamespace; ?>
        */
    }

    public function loadConfiguration(MvcEvent $e)
    {
    	$application   = $e->getApplication();
    	$sm            = $application->getServiceManager();
    	$sharedManager = $application->getEventManager()->getSharedManager();

    	$router = $sm->get('router');
    	$request = $sm->get('request');

    	/* $matchedRoute = $router->match($request);
    	if (null !== $matchedRoute) {
    		$sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
    				function($e) use ($sm) {
    					$sm->get('ControllerPluginManager')->get('Myplugin')
    					->doAuthorization($e); //pass to the plugin...
    				},2
    		);
    	} */
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

}
