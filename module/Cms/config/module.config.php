<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'cms/page' => 'Cms\Controller\PageController',
            // 'cms/page-rest' => 'Cms\Controller\PageRestController',
        	'cms/category' => 'Cms\Controller\CategoryController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'page' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/page[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'cms/page',
                        'action'     => 'index',
                    ),
                ),
            ),
            'page-ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/page/ajax',
                    'defaults' => array(
                        'controller' => 'cms/page',
                        'action'     => 'ajax',
                    ),
                ),
            ),
        	'page_rest' => array(
        			'type' => 'Zend\Mvc\Router\Http\Segment',
        			'options' => array(
        					'route' => '/page_rest[.:format][/:id]',
        					'constraints' => array(
        							'format' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							'id' => '[a-zA-Z0-9_-]*'
        					),
        					'defaults' => array(
        							'controller' => 'cms/page_rest',
        							'format' => 'json',
        					),
        			),
        	),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        /*
         * Ceci va activer la stratégie de rendu JSON. Toute action qui retournera un objet de type JsonModel passera par la le rendu JSON au lieu des vues classiques. Pas besoin de créer un fichier phtml pour ces actions.
         * */
        'strategies' => array(
                'ViewJsonStrategy',
        ),
    ),
);
