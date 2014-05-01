<?php
namespace Album\Event;

use Zend\EventManager\Event;

class Class2 {
	public function listen(Event $e) {
	    
		echo get_class($this) . ' has been called by ' . get_class($e->getTarget());
	}
}