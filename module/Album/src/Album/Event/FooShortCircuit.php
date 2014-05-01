<?php
namespace Album\Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class FooShortCircuit implements EventManagerAwareInterface
{
	protected $events;

	public function setEventManager(EventManagerInterface $events) {
		$this->events = $events;
		return $this;
	}

	public function getEventManager() {
		if (!$this->events) { $this->setEventManager(new EventManager(__CLASS__)); }
		return $this->events;
	}

	public function execute($obj)
	{
		$argv = array();
		$results = $this->getEventManager()->triggerUntil(__FUNCTION__, $this, $argv,
				function($v) use ($obj) {

				    return ($obj instanceof Album\Event\Foo);
				});
		 
		// if $obj instanceof foo, so stopped
		if ($results->stopped()) {
			return $results->last();
		}
		// continue...
		echo '<br />hei, i\'m continue :p';
	}
}