<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_Forum_Category extends Abstract_Controller_Admin {

		public function action_index()
		{

			if (!$this->user->can('Admin_Forum_Category_Index'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin forum index');
			}

			$categories = ORM::factory('Forum_Category')
				->find_all();

			$this->view = new View_Admin_Forum_Category_Index;

			$this->_nav('forum', 'category');
			$this->view->categories = $categories->as_array();
		}

		public function action_paginate() {

			if (!$this->user->can('Admin_Forum_Category_Paginate'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin forum category paginate');
			}


			if (DataTables::is_request())
			{
				$orm = ORM::factory('Forum_Category');

				$paginate = Paginate::factory($orm)
					->columns(array('id', 'title', 'locked'));

				$datatables = DataTables::factory($paginate)->execute();

				foreach ($datatables->result() as $category)
				{
					$datatables->add_row(array (
							$category->title,
							$category->locked,
							$category->id
						)
					);
				}

				$datatables->render($this->response);
			}
			else
				throw new HTTP_Exception_500();
		}

		public function action_retrieve()
		{

			if (!$this->user->can('Admin_Forum_Category_Retrieve'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin forum category retrieve');
			}

			$this->view = NULL;

			$category_id = $this->request->query('id');

			if ($category_id == NULL)
			{
				$category = ORM::factory('Forum_Category')
					->where('forum_category.title', '=', $this->request->query('title'))
					->find();
			}
			else
			{
				$category = ORM::factory('Forum_Category', $category_id);
			}

			$list = array(
				'id'          => $category->id,
				'title'        => $category->title,
				'description' => $category->description,
				'locked'      => $category->locked
			);
			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode($list));
		}

		public function action_save()
		{

			if (!$this->user->can('Admin_Forum_Category_Save'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin forum category save');
			}

			$values = $this->request->post();
			$this->view = NULL;

			if ($values['id'] == 0)
			{
				$values['id'] = NULL;
			}

			$this->response->headers('Content-Type', 'application/json');

			try
			{
				$category = ORM::factory('Forum_Category', $values['id']);
				$category->values($values, array('title', 'description', 'locked'));
				$category->save();

				$data = array(
					'action' => 'saved',
					'row'    => array(
						$category->title,
						$category->locked,
						$category->id
					)
				);
				$this->response->body(json_encode($data));
			} catch (ORM_Validation_Exception $e)
			{
				$errors = array();

				$list = $e->errors('models');

				foreach ($list as $field => $er)
				{
					if (!is_array($er))
					{
						$er = array($er);
					}

					$errors[] = array('field' => $field, 'msg' => $er);
				}

				$this->response->body(json_encode(array('action' => 'error', 'errors' => $errors)));
			}
		}

		public function action_delete()
		{

			if (!$this->user->can('Admin_Forum_Category_Delete'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin forum category delete');
			}

			$this->view = NULL;
			$values = $this->request->post();

			$category = ORM::factory('Forum_Category', $values['id']);
			$category->delete();

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('action' => 'deleted')));
		}
	}
