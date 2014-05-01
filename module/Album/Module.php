<?php

namespace Album;

use Album\Model\AlbumTable,
	Album\Model\Messages,
	Album\Model\MessagesTable,
	Album\Model\Couleurs,
	Album\Model\CouleursTable,
	Zend\Db\TableGateway\TableGateway,
	Zend\Db\ResultSet\ResultSet,
	Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Application\ConfigAwareInterface;
use Zend\ModuleManager\ModuleManager;
use Album\Model\Album;
// use Zend\EventManager\SharedEventManager;

class Module implements DependencyIndicatorInterface
{

    /**
     *Zend Framework 2.0.7 and 2.1 were released! They come with new features and more improvements. One of the improvements is ability to check other module already loaded before. It provide Zend\ModuleManager\Feature\DependencyIndicatorInterface.
     * The sample code is like the following :
     * If Mod1 is not loaded before Mod2, it will show the exception : Module “Album″ depends on module “Application″
     */
    public function getModuleDependencies()
    {
    	return array('Application');
    }

    public function init(ModuleManager $moduleManager)
    {
    	$event = $moduleManager->getEventManager()->getSharedManager();
    	$event->attach('Zend\Mvc\Application', 'bootstrap', function($e) {
    		// echo 'executed on bootstrap app process <br />';
    	});
    }

	public function onBootstrap(MvcEvent $event)
	{
	    $application   = $event->getApplication();
	    $sm            = $application->getServiceManager();
	    $eventManager  = $application->getEventManager();
	    // $sharedEvent = new SharedEventManager;
	    $sharedEventManager = $eventManager->getSharedManager();

		/* $eventManager->attach('sendTweet', function($e) {
			var_dump($e);
		}, 100);
		Ici, nous récupérons le gestionnaire d’évènements de l’application, et nous attachons une fonction à réaliser dès que l’évènement “sendTweet” est levé.
        Hélas… ceci ne fonctionne pas ! C’est la partie la plus compliquée du gestionnaire d’évènements, et l’astuce consiste à utiliser un gestionnaire d’évènements partagé.
		*/
		/*
		 * Imaginons un évènement tel que “send”. Plusieurs objets pourraient lancer un évènement nommé “send”, mais de nature complètement différentes (“send” peut aussi bien concerné un envoi de mail, que de SMS, ou d’un envoi de requête ou que sais-je !). Ce qui signifie que nos écouteurs (listeners) recevraient des évènements qui ne les concernent absolument pas ! C’est pourquoi chaque objet dispose de son propre gestionnaire d’évènements, avec ses propres évènements indépendants aux autres gestionnaires d’évènements.
         * Pour résoudre notre problème précédent, il faut utiliser un gestionnaire d’évènements partagé. Un gestionnaire d’évènements partagé est un gestionnaire qui, lui, est unique, et qui est injecté automatiquement dans chaque gestionnaire d’évènements. Modifions notre code de Module.php afin d’enregistrer l’évènement auprès du gestionnaire d’évènements partagé :
		 */

	    $sharedEventManager->attach('Album\Event\Foo', 'bar', function($e) {

	    	$event  = $e->getName();
	    	$target = get_class($e->getTarget());
	    	$params = json_encode($e->getParams());

	    	printf(
	    	'<br/>%s called on %s, using params %s',
	    	$event,
	    	$target,
	    	$params
	    	);
	    });

	    $sharedEventManager->attach('tweetId', 'sendTweet', function($e) {
	       	var_dump("tweet id : " . $e->getParam('id') . " was sent.");
	    }, 100);


	    $sharedEventManager->attach('FooShortCircuit', 'execute', function($e) {
	    	echo 'standard execution...';
	    });

		/*
		 * le gestionnaire d’évènements que nous recevons dans la méthode onBootstrap est justement le gestionnaire d’évènements de la boucle MVC principale. Ce qui signifie que ce gestionnaire d’évènements connaît les évènements lancés par le framework. A ce titre, si vous souhaitez ajouter un listener aux évènements décrits dans la classe Zend\Mvc\MvcEvent, vous pouvez le faire sans passer par le gestionnaire d’évènements partagés :
		 */
		$eventManager->attach(MvcEvent::EVENT_ROUTE, function($e) {
			// var_dump($e);
		}, 100);

		    //try to connect, and if not connected, then catch...
		    try {
		    	$dbInstance = $application->getServiceManager()
		    	->get('Zend\Db\Adapter\Adapter');
		    	$dbInstance->getDriver()->getConnection()->connect();
		    } catch (\Exception $ex) {
		    	$ViewModel = $event->getViewModel();
		    	$ViewModel->setTemplate('layout/layout');

		    	$content = new \Zend\View\Model\ViewModel();
		    	$content->setTemplate('error/mydberrorpagecustompage');

		    	//set $this->layout()->"content" variable
		    	//with error/mydberrorpagecustompage.phtml
		    	$ViewModel->setVariable('content', $sm->get('ViewRenderer')
		    			->render($content));

		    	exit($sm->get('ViewRenderer')->render($ViewModel));
		    }
		    // Still want to using EventManager? use at Wildcard attachment to make $callback available in all events (we can’t rely on dispatch.error, render.error, or other), like the following
		    // using '*' to make $callback available in all events.
		    // $eventManager->attach('*', array($this, 'dbInstanceError' ), 1000);


		    /**
		     * Disable Layout in specific Module
		     */
		    /*$sharedEvents        = $e->getApplication()->getEventManager()->getSharedManager();
		    $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
		    	$result = $e->getResult();
		    	if ($result instanceof \Zend\View\Model\ViewModel) {
		    		$result->setTerminal($e->getRequest()->isXmlHttpRequest());
		    		//if you want no matter request is, the layout is disabled, you can
		    		//set true : $result->setTerminal(true);
		    	}
		    }); */
		    /*
		     * $sharedEvents        = $e->getApplication()->getEventManager()->getSharedManager();
            $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
             function($e) {
                $result = $e->getResult();
                if ($result instanceof \Zend\View\Model\ViewModel) {
                    $result->setTerminal($e->getRequest()->isXmlHttpRequest());
                   //if you want no matter request is, the layout is disabled, you can
                   //set true : $result->setTerminal(true);
                }
            }); */
	}

	/*public function dbInstanceError(MvcEvent $e)
	{
		$application   = $e->getTarget();
		$sm            = $application->getServiceManager();

		//try to connect, and if not connected, then catch...
		try {
			$dbInstance = $application->getServiceManager()
			->get('Zend\Db\Adapter\Adapter');
			$dbInstance->getDriver()->getConnection()->connect();
		} catch (\Exception $ex) {
			$ViewModel = $e->getViewModel();
			$ViewModel->setTemplate('layout/layout');

			$content = new \Zend\View\Model\ViewModel();
			$content->setTemplate('error/mydberrorpagecustompage');

			//set $this->layout()->"content" variable
			//with error/mydberrorpagecustompage.phtml
			$ViewModel->setVariable('content', $sm->get('ViewRenderer')
					->render($content));

			echo $sm->get('ViewRenderer')->render($ViewModel);
			$e->stopPropagation();
		}
	}*/

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig()
    {
    	return array(
    			'initializers' => array(
    					function ($instance, $sm) {
    						if ($instance instanceof ConfigAwareInterface) {
    							$locator = $sm->getServiceLocator();
    							$config  = $locator->get('Config');
    							$instance->setConfig($config['application']);
    						}
    					}
    			),
    			'factories' => array(
    					'testfactory' => function(\Zend\Mvc\Controller\ControllerManager $sm)  {

    						// @var $sm \Zend\Mvc\Controller\ControllerManager */
    						$locator = $sm->getServiceLocator();
    						$config  = $locator->get('Config');

    						$testcontroller = new Controller\AlbumController();
    						$testcontroller->setTestParam($config);
    						return $testcontroller;
    					}
    			),

    			'abstract_factories' => array(
    					'Album\Service\CommonControlAppAbstractFactory'
    			),
    	);
    }

    public function getViewHelperConfig()
    {
    	return array(
    			'invokables' => array(
    					'lesmessages'   => 'Album\View\Helper\Lesmessages'
    					// ...
    			),
    			'factories' => array(
    					'test_helper' => function($sm) {
    						$helper = new View\Helper\Testhelper ;
    						return $helper;
    					},
    					/* 'lesmessages'   => function($sm) {
    					 $helper = new Lesmessages($sm) ;
    					return $helper;
    					} */
    			)
    			// OR, you can SIMPLIFY that by configuring it as an invokable in module.config.php or here in Module.php
    			/*
    			 *         'invokables'=> array(
    			 		'test_helper' => 'Test\View\Helper\Testhelper'
    			 )
    					call it in view like this : echo $this->test_helper("me","e");
    					*/
    	);

    	/*ou pour faire simple, dans le fichier module.config.php du module :
    	 *
    	* 'view_helpers' => array(
    			'invokables' => array(
    					'displayAvatar' => 'MyModule\View\Helper\DisplayAvatar'
    			)
    	)
    	*/
    }

    public function getServiceConfig()
    {
        /*
         * return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
         */
        return array(
            'initializers' => array(
            		function ($instance, $sm) {
            			if ($instance instanceof ConfigAwareInterface) {
            				$locator = $sm->getServiceLocator();
            				$config  = $locator->get('Config');
            				$instance->setConfig($config['application']);
            			}
            		}
            ),
            'factories' => array(
                'Album\Model\AlbumTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AlbumTable($dbAdapter);
                    // $tableGateway = $sm->get('AlbumTableGateway');
                    // $table = new AlbumTable($tableGateway);
                    return $table;
                },
                /* 'AlbumTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Album());
                	return new TableGateway('albums', $dbAdapter, null, $resultSetPrototype);

                	$resultSetPrototype = new HydratingResultSet(
                			new ArraySerializableHydrator(),
                			new Category()
                	);

                	return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                }, */
                /*
                 * verifier ceci
                'Album\Model\MessagesTable' =>  function($sm) {
                	$tableGateway = $sm->get('MessagesTableGateway');
                	$table = new MessagesTable($tableGateway);
                	return $table;
                },
                'MessagesTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Messages());
                	return new TableGateway('messages', $dbAdapter, null, $resultSetPrototype);
                }, */
                'Tutoriels\Model\CouleursTable' =>  function($sm) {
                	$tableGateway = $sm->get('CouleursTableGateway');
                	$table = new CouleursTable($tableGateway);
                	return $table;
                },
                'CouleursTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Couleurs());
                	return new TableGateway('couleurs', $dbAdapter, null, $resultSetPrototype);
                },
                'MyService' => 'Album\Invokables\SimpleServiceFactory',
                // on peut utiliser un callback à la place d’une classe factory :
                // notre fonction nous retourne notre objet configuré
                /* 'MyService' => function($sm) {
                	// on crée notre objet
                	$myService = new Invokables\SimpleService();

     	            // on récupère une autre dépendance que l'on injecte
                	$dependency = $sm->get('AnyDependency');

                	// on le passe à notre classe
                	$myService->setParam($dependency);

                	// on retourne l'objet
                	return $myService;
                }*/
            ),
            'invokables' => array(
		        'simple' => 'Album\Invokables\SimpleService',
		    ),
		    /*'abstract_factories' => array(
		    		'Album\Service\CommonModelTableAbstractFactory',
		    ),*/
		    'aliases' => array(
		        'alias_simple' => 'simple',
		    )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}