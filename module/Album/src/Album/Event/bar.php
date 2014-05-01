<?php
namespace Album\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Bar implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
	protected $listeners = array();

	public function attach(EventManagerInterface $e)
	{
		$this->listeners[] = $e->attach('Bar.pre', array($this, 'load'));
		$this->listeners[] = $e->attach('Bar.post', array($this, 'save'));
	}
	
	public function detach(EventManagerInterface $e)
	{
		foreach ($this->listeners as $index => $listener) {
			if ($e->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	}

	public function load(EventInterface $e) { echo '<p>load...</p>'; }
	public function save(EventInterface $e) { echo '<p>save...</p>'; }
}