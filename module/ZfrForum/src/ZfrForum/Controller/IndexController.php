<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZfrForum\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	var_dump($this->getEventManager());
    	// $smtp = $this->serviceManager->get('common.mailer.smtp_transport');
    	// var_dump($this->getServiceLocator()->get('common.mailer.smtp_transport'));
    	var_dump($this->getServiceLocator()->has('mailer'));
//     	$mailer = $this->locator->get('common.service.mailer');
//     	$message = $mailer->createHtmlMessage('toto@gmail.com', 'Test', 'application/index/my-template');
//     	$mailer->send($message);
    	
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
}
