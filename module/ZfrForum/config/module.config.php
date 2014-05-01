<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ZfrForum\Controller\Index' => 'ZfrForum\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'ZfrForum' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/ZfrForum',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'ZfrForum\Controller',
                        'controller'    => 'Index',
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
    ),
	'mailer' => array(
        'default_message' => array(
            'from' => array(
                'email' => 'my@email.com',
                'name'  => 'Johnny Halliday'
            ),
            'encoding' => 'utf-8'
        ),
        'smtp_options' => array(
            'host'              => 'host',
            'port'              => 465,
            'connection_class'  => 'login',
            'connection_config' => array(
                'username' => 'username',
                'password' => 'password',
                'ssl'      => 'ssl'
		    )
	    )
	)
);
