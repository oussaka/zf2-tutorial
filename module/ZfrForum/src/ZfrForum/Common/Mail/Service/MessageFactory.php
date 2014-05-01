<?php

namespace Common\Mail\Service;

use Zend\Mail\Message;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Message
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config  = $serviceLocator->get('Configuration');

        $options = $config['mailer']['default_message'];
        $from    = $options['from'];

        $message = new Message();
        $message->addFrom($from['email'], $from['name'])
                ->setEncoding($options['encoding']);

        return $message;
    }
}