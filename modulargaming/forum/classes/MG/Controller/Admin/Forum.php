<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_Forum extends Abstract_Controller_Admin {

	public function action_index()
	{
		$this->redirect(Route::get('forum.admin.category.index')->uri());
	}

/**
	public function action_search()
	{
		if ( ! $this->user->can('Admin_Forum_Search') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin forum search');
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
**/

}
