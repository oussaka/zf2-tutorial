<?php
namespace Application;

return array(
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[home]',
                    'defaults' => array(
                           '__NAMESPACE__' => 'Application\Controller',
                           'controller'    => 'Index',
                           'action'        => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
            'album' => array(
                    'type'    => 'Literal',
                    'options' => array(
                            'route'    => '/album',
                            'defaults' => array(
                                    // '__NAMESPACE__' => 'Application\Controller',
                                    'controller'    => 'Album',
                                    'action'        => 'index',
                            ),
                    ),
            ),
            'changelocale' => array(
                    'type' => 'Zend\Mvc\Router\Http\Segment',
                    'options' => array(
                            'route' => '/changelocale[/:locale[/:redirecturl]]',
                            'defaults' => array(
                                    'controller'    => 'Application\Controller\Translator',
                                    'action'        => 'changelocale',
                                    'locale'        => '',
                                    'redirecturl'   => ''
                            )
                    ),
            ),
        ),
    ),
    'session' => array(
            'remember_me_seconds'  => 1200,
            'use_cookies'          => true,
            'cookie_httponly'      => true,
            'cookie_domain'        => 'aromatix.fr',
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US', // essayez plusieurs locales (EX: es_ES, it_IT, ar_SY) et jetez un coup d'oeil au site
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                // 'text_domain' => 'application', // domaine de traduction
            ),
            array(
                'type'     => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ),
            array(
                'type' => 'phpArray',
                'base_dir' => './vendor/zendframework/zendframework/resources/languages/',
                'pattern'  => 'fr/Zend_Validate.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Translator' => 'Application\Controller\TranslatorController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
            'driver' => array(
                    __NAMESPACE__ . '_driver' => array(
                            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'cache' => 'array',
                            'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
                    ),
                    'orm_default' => array(
                            'drivers' => array(
                                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                            )
                    )
            ),
            'eventmanager' => array(
                    'orm_default' => array(
                            'subscribers' => array(
                                    'Gedmo\Timestampable\TimestampableListener',
                                    // 'Gedmo\SoftDeleteable\SoftDeleteableListener',
                                    // 'Gedmo\Translatable\TranslatableListener',
                                    // 'Gedmo\Blameable\BlameableListener',
                                    // 'Gedmo\Loggable\LoggableListener',
                                    // 'Gedmo\Sluggable\SluggableListener',
                                    // 'Gedmo\Sortable\SortableListener',
                                    // 'Gedmo\Tree\TreeListener',
                            ),
                    ),
            )
    )
);
