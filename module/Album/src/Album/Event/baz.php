<?php
namespace Album\Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Baz  implements EventManagerAwareInterface
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
	 
	public function get($id)
	{
		$params = compact('id');
		$results = $this->getEventManager()->trigger('Bar.pre', $this, $params);

		// If an event stopped propagation, return the value
		if ($results->stopped()) {
			return $results->last();
		}

		// do some work...

		$this->getEventManager()->trigger('Bar.post', $this, $params);
	}
}