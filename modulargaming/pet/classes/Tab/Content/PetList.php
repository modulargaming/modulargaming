<?php defined('SYSPATH') OR die('No direct script access.');
 
class Tab_Content_PetList extends Abstract_Tab_Content {

	public function __construct(array $pets)
	{
		$this->view = new View_Tab_Content_PetList;
		$this->view->pets = $pets;
	}

}
