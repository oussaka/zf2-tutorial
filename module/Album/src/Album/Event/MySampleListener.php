<?php
//module/YourModule/src/YourModule/Event/MySampleListener.php
namespace Album\Event;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;

class MySampleListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('eventName', array($this, 'doEvent'));
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function doEvent(EventInterface $event)
    {
        
        echo "<br />" . $event->getName() . ' event is called with params: param id  = '.$event->getParam('id');
    }
}