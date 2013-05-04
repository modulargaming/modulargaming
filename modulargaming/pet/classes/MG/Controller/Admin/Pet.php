<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_Pet extends Abstract_Controller_Admin {

	public function action_index()
	{
		$id = $this->request->param('id');

		if ( ! $this->user->can('Admin_Pet_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}

		$this->redirect(Route::get('pet.admin.specie.index')->uri());
		$this->view = new View_Admin_Pet_Index;
	}

	public function action_search()
	{
		if ( ! $this->user->can('Admin_Pet_Search') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets search');
		}


		$this->view = NULL;

		$type = $this->request->query('type');
		$item_name = $this->request->query('name');

		if($type == 'pet-specie')
		{
			$items = ORM::factory('Pet_Specie')
			->where('pet_specie.name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		else if($type == 'pet-colour')
		{
			$items = ORM::factory('Pet_Colour')
			->where('pet_colour.name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}

		$list = array();

		foreach ($items as $item) {
			$list[] = $item->name;
		}

		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
}
