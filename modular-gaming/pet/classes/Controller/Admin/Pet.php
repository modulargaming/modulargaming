<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pet extends Abstract_Controller_Admin {
	
	public function action_index()
	{

		if ( ! $this->user->can('Admin_Pet_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}

		$this->view = new View_Admin_Pet_Index;
	}

}
