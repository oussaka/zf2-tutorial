<?php
namespace Cms;
use Cms\Model\PageTable,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Classe de configuration du Module CMS
 * On implemente les interfaces des fonctionnalités de module que nous allons utiliser
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Construit la configuration des services
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                //On enregistre notre service d'accès à la base de données sous la clé 'Cms\Model\PageTable'
                'Cms\Model\PageTable' =>  function ($sm)
                    {
                        //On récupère le dbAdaptater initialisé dans le Module.php de l'appplication
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $table = new PageTable($dbAdapter);
                        return $table;
                    },
            ),
        );
    }
}
