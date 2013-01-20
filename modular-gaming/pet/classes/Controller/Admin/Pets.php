<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pets extends Abstract_Controller_Admin {
	
	public function action_index()
	{

		if ( ! $this->user->can('Admin_Pets_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}

		$pets = ORM::factory('User_Pet')
			->find_all();

		$this->view = new View_Admin_Pets_Index;
		$this->view->pets = $pets->as_array();
	}

}
