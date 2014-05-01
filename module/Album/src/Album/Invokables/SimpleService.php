<?php

namespace Album\Invokables;

class SimpleService
{
	protected $var;
	private $name = "Kaabachi";

	public function __construct() {
		echo 'construct appelé : SimpleService';
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName( $name )
	{
		$this->name = $name;
	}

	public function setParam($value)
	{
		$this->var = $value;
	}

	public function doSomething()
	{
		// ...
		// $result = '...';
		return $result;
	}
}