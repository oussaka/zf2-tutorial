<?php

namespace Application\Service;

class ErrorHandling
{
    protected $logger;

    function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Exception $e
     */
    function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $i = 1;
        do {
            $messages[] = $i++ . ": " . $e->getMessage();
        } while ($e = $e->getPrevious());

        $log = "Exception:n" . implode("n", $messages);
        $log .= "nTrace:n" . $trace;

        $this->logger->err($log);
        // there are functions for that : emerg(), alert(), err(), warn(),notice(), debug(), info(), see https://github.com/zendframework/zf2/blob/master/library/Zend/Log/Logger.php
    }
}