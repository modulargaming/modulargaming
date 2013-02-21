<?php defined('SYSPATH') OR die('No direct script access.');
 
class Tab {

	/**
	 * @var String $name
	 */
	public $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function render()
	{
		return $this->name;
	}

}
