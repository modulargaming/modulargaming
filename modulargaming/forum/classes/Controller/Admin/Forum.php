<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Forum extends Abstract_Controller_Admin {

	private $_category; // Forum Category

	/**
	 * Load the Forum category if an ID is specified, if the category isn't found throw a 404 exception.
	 */
	public function before()
	{
		parent::before();

		$id = $this->request->param('id');

		if ($id !== NULL)
		{
			$this->_category = ORM::factory('Forum_Category', $id);

			if ( ! $this->_category->loaded())
			{
				throw HTTP_Exception::factory('404', 'No such category');
			}
		}
	}

	/**
	 * Display a list of categories.
	 */
	public function action_index()
	{
		if ( ! $this->user->can('Admin_Forum_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin forum index');
		}

		$categories = ORM::factory('Forum_Category')
			->find_all();

		$this->view = new View_Admin_Forum_Index;
		$this->view->categories = $categories->as_array();
	}

	public function action_create()
	{
		if ($this->request->method() == HTTP_REQUEST::POST)
		{
			try
			{
				ORM::factory('Forum_Category')
					->create_category($this->request->post(), array(
						'title',
						'locked',
						'description'
					));

				Hint::success('Forum Category created');

				$this->redirect(Route::get('forum.admin')->uri());
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Admin_Forum_Create;
	}

	public function action_edit()
	{
		// Edit!
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$this->_category->values($this->request->post(), array(
					'title',
					'description',
					'locked'
				));
				$this->_category->save();

				Hint::success('Forum Category was updated!');
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
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
		$this->view->category = $this->_category;
	}

	public function action_delete()
	{
		// Get possible categories to move posts to.
		$categories = ORM::factory('Forum_Category')
			->where('id', '<>', $this->_category->id)
			->find_all();

		if ($this->request->method() == HTTP_Request::POST)
		{
			$post = $this->request->post();

			// Either delete all posts, OR move them to another category.
			if (isset($post['delete']))
			{
				// TODO, Possible change the API to only have move_topics AND keep delete_all_topics as a sql "rule".
				$this->_category->delete_all_topics();
			}
			else
			{
				$this->_category->move_all_topics_to($post['move']);
			}

			// Now we can delete the category.
			$this->_category->delete();

			$this->redirect(Route::get('forum.admin')->uri());
		}

		$this->view = new View_Admin_Forum_Delete;
		$this->view->category = $this->_category;
		$this->view->categories = $categories;
	}

}
