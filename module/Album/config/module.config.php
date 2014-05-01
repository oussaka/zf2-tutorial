<?php
namespace Album;

return array(
    // ServiceLocator ControllerLoader – Get Module Configuration from Controller
    'Test' => array(
            'moduletitle' => 'ini title module test',
            'moduledesc' => 'Ini desc module test'
    ),
    'controllers' => array(
        'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
            'Album\Controller\Couleurs' => 'Album\Controller\CouleursController',
        ),
        /*
         * abstract_factories : Unknown Services. In this case, if SM could not find controllers in invokables, the SM will turn to it whenever canCreateServiceWithName return true; ( controllers is service that called automatically by mvc stack )
         */
        'abstract_factories' => array(
        	'Album\Service\CommonControlAppAbstractFactory',
        ),
    ),
    'controller_plugins' => array(
            'invokables' => array(
                    'HeadTitle' => 'Album\Controller\Plugin\HeadTitle',
            )
    ),
    'router' => array(
        'routes' => array(
            'album' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/album[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Album\Controller\Album',
                        'action'     => 'index',
                    ),
                ),
            ),
        	'paginator' => array(
        		'type'    => 'segment',
        		'options' => array(
        				'route'    => '/paginator[/:action][/:id][/page/:page][/order_by/:order_by][/:order]',
        				'constraints' => array(
        						'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
        						'id'     => '[0-9]+',
        						'page' => '[0-9]+',
        						'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
        						'order' => 'ASC|DESC',
        				),
        				'defaults' => array(
        						'controller' => 'Album\Controller\Album',
        						'action'     => 'paginator',
        				),
        		),
        	),
       		'ajaxexecution' => array(
			        'type'    => 'segment',
			        'options' => array(
			            'route'    => '/ajaxexecution',
			            'defaults' => array(
			                'controller' => 'Album\Controller\Couleurs',
			                'action'     => 'ajaxexecution',
			            ),
			        ),
			    ),
			    'editcouleur' => array(
			        'type'    => 'segment',
			        'options' => array(
			            'route'    => '/editcouleur',
			            'defaults' => array(
			                'controller' => 'Album\Controller\Couleurs',
			                'action'     => 'editcouleur',
			            ),
			        ),
			    ),
			    'addcouleur' => array(
			        'type'    => 'segment',
			        'options' => array(
			            'route'    => '/addcouleur',
			            'defaults' => array(
			                'controller' => 'Album\Controller\Couleurs',
			                'action'     => 'addcouleur',
			            ),
			        ),
			   	),
        ),
    ),
    /*
     * 'service_manager' => array(
        'abstract_factories' => array(),
        'aliases' => array(),
        'factories' => array(),
        'invokables' => array(),
        'services' => array(),
        'shared' => array(
            // Usually, you'll only indicate services that should _NOT_ be
            // shared -- i.e., ones where you want a different instance
            // every time.
            'MyTable' => false,
        ),
        /**
         * Override your existing Services.
         * /
        'allow_override' => array(
            'MyService' => true,
        ),
    ),
     */
	'service_manager' => array(
		'invokables' => array(
			// 'my-foo' => 'MyModule\Foo\Bar'
		),
	    /*'abstract_factories' => array(
	    		'Album\Service\CommonModelTableAbstractFactory',
	    ),*/
	    /**
	     * It initialize the service whenever service created. It can reduce the redundance the injections to services.
	     */
	    'initializers' => array(
	    		function ($instance, $sm) {
	    			if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
	    				$instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
	    			}
	    		}
	    ),
	),
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    	'template_map' => array(
    		'paginator-slide' => __DIR__ . '/../view/layout/slidePaginator.phtml',
    	),
    ),
    'application' => array(
    		'setting_1' => 234,
    )
);