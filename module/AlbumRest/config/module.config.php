<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AlbumRest\Controller\AlbumRest' => 'AlbumRest\Controller\AlbumRestController',
        ),
    ),
    'router' => array(
        'routes' => array(
        	'album-rest' => array(
        			'type'    => 'segment',
        			'options' => array(
        					'route'    => '/album-rest[/:id]',
        					'constraints' => array(
        							'id'     => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller' => 'AlbumRest\Controller\AlbumRest',
        					),
        			),
        	    'may_terminate' => true,
        	    'child_routes' => array(
        	    		'default' => array(
        	    				'type'    => 'Segment',
        	    				'options' => array(
        	    						'route'    => '/get-all',
        	    						'constraints' => array(
        	    								'action'     => 'get-list',
        	    						),
        	    						'defaults' => array(
        	    						),
        	    				),
        	    		),
        	    ),
        	),
            'module-name-here' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/module-specific-root',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'ZendSkeletonModule\Controller',
                        'controller'    => 'Skeleton',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
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
         * Ceci va activer la strat�gie de rendu JSON. Toute action qui retournera un objet de type JsonModel passera par la le rendu JSON au lieu des vues classiques. Pas besoin de cr�er un fichier phtml pour ces actions.
         * */
        'strategies' => array(
                'ViewJsonStrategy',
        ),
    ),
);
