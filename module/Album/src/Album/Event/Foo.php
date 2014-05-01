<?php
namespace Album\Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Foo implements EventManagerAwareInterface
{
	protected $events;

	public function setEventManager(EventManagerInterface $events)
	{
		$this->events = $events;
		return $this;
	}

	public function getEventManager()
	{
		if (!$this->events) {
			$this->setEventManager(new EventManager(__CLASS__));
		}
		return $this->events;
	}

	public function bar($baz, $bat = null) {
		$params = compact('baz', 'bat');
		$this->getEventManager()->trigger(__FUNCTION__, $this, $params);
	}
}