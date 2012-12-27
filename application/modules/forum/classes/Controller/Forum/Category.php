<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Category extends Controller_Frontend {

	public function action_view()
	{
		$id = $this->request->param('id');

		$category = ORM::factory('Forum_Category', $id);

		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}

		$topics = $category->topics->find_all();

		$this->view = new View_Forum_Category;
		$this->view->category = $category;
		$this->view->topics = $topics;
	}

	public function action_create()
	{
		$id = $this->request->param('id');

		$category = ORM::factory('Forum_Category', $id);

		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum category not found');
		}

		$topics = $category->topics->find_all();

		$this->view = new View_Forum_Create;
		$this->view->category = $category;
	}

}
