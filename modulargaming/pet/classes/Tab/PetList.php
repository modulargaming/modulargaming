<?php defined('SYSPATH') OR die('No direct script access.');
 
class Tab_PetList extends Abstract_Tab {

	public function __construct(Model_User $user, array $pets)
	{
		$this->view = new View_Tab_PetList;
		$this->view->user = $user;
		$this->view->pets = $pets;
	}

}
