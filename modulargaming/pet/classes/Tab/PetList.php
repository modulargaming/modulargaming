<?php defined('SYSPATH') OR die('No direct script access.');
 
class Tab_PetList extends Abstract_Tab {

	public function __construct(array $pets)
	{
		$this->view = new View_Tab_PetList;
		$this->view->pets = $pets;
	}

}
