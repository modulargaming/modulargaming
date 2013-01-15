<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Forum extends Abstract_Controller_Admin {

	public function action_index()
	{

		if ( ! $this->user->can('Admin_Forum_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin forum index');
		}


		$categories = ORM::factory('Forum_Category')
			->find_all();

		$this->view = new View_Admin_Forum_Index;
		$this->view->categories = $categories->as_array();
		
	}

	public function action_edit()
	{
		$id = $this->request->param('id');

		$category = ORM::factory('Forum_Category', $id);

		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such category');
		}

		/*
		if ($this->request->is_ajax())
		{
			$this->response->headers('Content-Type', 'application/json');
			return $this->response->body(json_encode(array(
				'username' => $user->username,
				'email'    => $user->email,
			)));
		}
		*/

		$this->view = new View_Admin_Forum_Edit;
		$this->view->category = $category;
	}

}
