<?php

namespace Application;

use Application\Service\ErrorHandling as ErrorHandlingService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogWriterStream;

use Zend\Mvc\ModuleRouteListener;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container,
	Album\Model\Messages,
	Album\Model\MessagesTable,
	Zend\Db\TableGateway\TableGateway,
	Zend\Db\ResultSet\ResultSet;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{

	public function init(ModuleManager $moduleManager)
	{
		$events = $moduleManager->getEventManager()->getSharedManager();
		$events->attach('Zend\Mvc\Application', 'bootstrap', function($e) {
			// echo 'executed on bootstrap app process <br />';
		});
		// $events->attach('Zend\Mvc\Application', '*', array($this, 'onApplicationEvent'), 100);
		// var_dump($events);
	}

	public function onApplicationEvent($e)
	{
		echo $e->getName() . nl2br("\n");
	}

    public function onBootstrap(MvcEvent $e)
    {
        // container de session
        $sessionContainer = new Container('locale');

        // teste si la langue en session existe
        if(!$sessionContainer->offsetExists('mylocale')){
        	// n'existe pas donc on ajoute la langue du navigateur
        	$sessionContainer->offsetSet('mylocale', \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        }

        // mise en place du service de traduction
        // ne fonctionne pas avec la version 2.2.* du Framework
        /** @var $translator \Zend\I18n\Translator\Translator */
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator ->setLocale($sessionContainer->mylocale)
                    ->setFallbackLocale('en_US');


        $serviceManager = $e->getApplication()->getServiceManager();

        $translator = $serviceManager->get('translator');
        $locale     = new \Locale();

        $httplanguages = getenv('HTTP_ACCEPT_LANGUAGE');
        if (empty($httplanguages) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        	$httplanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->initializeSession($e);

        /* Ajout de la sélection automatique de la langue en fonction du navigateur de l’utilisateur
         *
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        ->setFallbackLocale('fr_FR');
        */

        $eventManager->attach('route', array($this, 'onPreRoute'), 100);   // traduction Url Ca se passe ici
        // $moduleRouteListener->attach($eventManager);

        $eventManager->attach('dispatch.error', function($event){
            $exception = $event->getResult()->exception;

        	if ($exception) {
        		$sm = $event->getApplication()->getServiceManager();
        		$service = $sm->get('Application\Service\ErrorHandling');
        		$service->logException($exception);
        	}
        });


            /* $event->attach('dispatch', function($e) {
            	echo 'executed on dispatch process';
            });*/
            /* $event = $e->getApplication()->getEventManager();

            $event->attach('route', function($e) {
            	echo 'executed on route process';
            });

        	$event->attach('dispatch', function($e) {
        		echo 'executed on dispatch process';
        	});

    		$event->attach('dispatch.error', function($e) {
    			echo $e->getParam('exception');
    			echo 'executed only if found an error <br />, for ex : sm not found <br />';
    		});

			$event->attach('render', function($e) {
				$e->getViewModel()->setVariable('test', 'test');
				echo 'executed on render process <br />';
			});

			$event->attach('render.error', function($e) {
				echo 'executed on render  error found';
			});

			$event->attach('finish', function($e) {
				echo 'executed on finish process';
			});

            $event->attach(MvcEvent::EVENT_ROUTE, function($e) {
                var_dump($e);
            }, 100);
            */
    }

    public function onPreRoute($e)
    {
    	$app      = $e->getTarget();
    	$serviceManager       = $app->getServiceManager();
    	$serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
        	/* 'Zend\Loader\ClassMapAutoloader' => array(
        			__DIR__ . '/autoload_classmap.php',
        	), */
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
    	return array(
    			'invokables' => array(
    					'displayAvatar' => 'Application\View\Helper\DisplayAvatar',
    					'lesmessages'   => 'Album\View\Helper\Lesmessages'
    					// ...
    			)
    	);

    	/* ou pour faire simple, dans le fichier module.config.php du module :
    	 *
    	 * 'view_helpers' => array(
    			'invokables' => array(
    					'displayAvatar' => 'MyModule\View\Helper\DisplayAvatar'
    			)
    	)
    	*/
    }

    public function initializeSession($em)
    {
    	$config = $em->getApplication()
			    	->getServiceManager()
			    	->get('Config');

    	$sessionConfig = new SessionConfig();
    	$sessionConfig->setOptions($config['session']);

    	$sessionManager = new SessionManager($sessionConfig);
    	$sessionManager->start();

    	Container::setDefaultManager($sessionManager); //au cas ou on utilise plusieurs SessionManagers

    }

    /**
     * Construit la configuration des services.
     * Afin de toujours utiliser la même instance de notre PageTable, nous allons utiliser le ServiceManager pour définir comment en créer une. Cela se fait assez facilement dans la classe Module de notre module: nous allons créer une méthode getServiceConfig() qui sera automatiquement appelé par le ModuleManager. Nous serons ainsi capable de récupérer cette instance dans nos controleurs quand nous en aurons besoin.
     */
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    				//On enregistre notre service d'accès à la base de données sous la clé 'Cms\Model\PageTable'
    				/*'Cms\Model\PageTable' =>  function ($sm)
    				{
    					//On récupère le dbAdaptater initialisé dans le Module.php de l'appplication
    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					$table = new PageTable($dbAdapter);
    					return $table;
    				},*/
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
    				},

    				'Application\Service\ErrorHandling' =>  function($sm) {
    					$logger = $sm->get('Zend\Log');
    					$service = new ErrorHandlingService($logger);
    					return $service;
    				},
    				'Zend\Log' => function ($sm) {
    					$filename = 'log_' . date('F') . '.txt';
    					$log = new Logger();
    					$writer = new LogWriterStream('./data/logs/' . $filename);
    					$log->addWriter($writer);

    					return $log;
    				},
    			),
    	);
    }


    public function setRoute()
    {
        echo "dans le set route";
    }

}
