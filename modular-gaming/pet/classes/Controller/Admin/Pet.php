<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pet extends Abstract_Controller_Admin {
	
	public function action_index()
	{
		$id = $this->request->param('id');

		if ( ! $this->user->can('Admin_Pet_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}
		
//		$this->redirect(Route::get('pet.admin.specie'));
//		$this->redirect(Route::get('pet.admin.specie')->uri(array('id' => $this->user->id)));
		$this->redirect(Route::get('pet.admin.specie')->uri(array('id' => $id)));
		$this->view = new View_Admin_Pet_Index;
	}

}
