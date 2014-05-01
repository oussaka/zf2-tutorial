<?php
namespace Cms\Listener;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger;
use Cms\Listener\Exception;

class Logging {
    
    public static function logOutput($event)
    {
        $logdir = dirname(__DIR__) . "/../../../../data/logs/";
        $stream = @fopen($logdir . "logs.log", 'a', false);
        if (!$stream) {
            throw new Exception\InvalidFileException( 'Failed to open stream' );
        }
        
        $writer = new Stream($stream);
        $logger = new Logger();
        $logger->addWriter($writer);
        
        switch ($event->getName()) {
            case ("isValid.post"):
                list($id, $title, $artist, $submit) = each( $event->getParams() );
                $message = sprintf(
                        "Post form validation: %s %s ", $title, $artist, $event->getParam('id')
                );
                $logger->info($message);
                break;

            case ("isValid.pre"):
                list($id, $title, $artist, $submit) = each( $event->getParams() );
                $message = sprintf(
                        "Pre form validation: %s %s ", $title, $artist, $event->getParam('id')
                );
                $logger->info($message);
                break;
        }
    }
}