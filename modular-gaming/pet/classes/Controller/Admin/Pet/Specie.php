<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pet_Specie extends Controller_Admin_Pet {
	
	public function action_index()
	{

		if ( ! $this->user->can('Admin_Pet_Specie_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}

		$species = ORM::factory('Pet_Specie')
			->find_all();

		$this->view = new View_Admin_Pet_Specie_Index;
		$this->_nav('pet', 'specie');
		$this->view->species = $species->as_array();
	}

}
