<?php 
namespace Album\Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Class1 implements EventManagerAwareInterface {
    
    protected $events;
    
    public function setEventManager(EventManagerInterface $events) {
        $this->events = $events;
        return $this;
    }
    
    public function getEventManager() {
        if (!$this->events) { $this->setEventManager(new EventManager(__CLASS__)); }
        return $this->events;
    }
    
    public function run() {
        $this->getEventManager()->trigger('cls', $this);
    }
}
