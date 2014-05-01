<?php
namespace Album\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommonModelTableAbstractFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{
		return (substr($requestedName, -5) === 'Table');
	}

	public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
	{
		$db = $locator->get('Zend\Db\Adapter\Adapter');
		$tablemodel = new $requestedName;
		$tablemodel->setDbAdapter($db);

		return $tablemodel;
	}
}