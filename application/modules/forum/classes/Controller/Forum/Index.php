<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Index extends Controller_Frontend {

	public function action_index()
	{
		$categories = ORM::factory('Forum_Category')
			->find_all();

		Breadcrumb::add('Forum', Route::url('forum'));

		$this->view = new View_Forum_Index;
		$this->view->categories = $categories;
	}

}