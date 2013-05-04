<?php defined('SYSPATH') OR die('No direct script access.');

	class MG_Controller_Admin_Pet_Colour extends Abstract_Controller_Admin {

		public function action_index()
		{

			if (!$this->user->can('Admin_Pet_Colour_Index'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour index');
			}

			$colours = ORM::factory('Pet_Colour')
				->find_all();

			$this->view = new View_Admin_Pet_Colour_Index;
			$this->_nav('pet', 'colour');
			$this->view->colours = $colours->as_array();
		}

		public function action_paginate() {

			if (!$this->user->can('Admin_Pet_Colour_Paginate'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour paginate');
			}


			if (DataTables::is_request())
			{
				$orm = ORM::factory('Pet_Colour');

				$paginate = Paginate::factory($orm)
					->columns(array('id', 'name', 'locked'));

				$datatables = DataTables::factory($paginate)->execute();

				foreach ($datatables->result() as $colour)
				{
					$datatables->add_row(array (
							$colour->name,
							$colour->locked,
							$colour->id
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

			if (!$this->user->can('Admin_Pet_Colour_Retrieve'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour retrieve');
			}

			$this->view = NULL;

			$colour_id = $this->request->query('id');

			if ($colour_id == NULL)
			{
				$colour = ORM::factory('Pet_Colour')
					->where('pet_colour.name', '=', $this->request->query('name'))
					->find();
			}
			else
			{
				$colour = ORM::factory('Pet_Colour', $colour_id);
			}

			$list = array(
				'id'          => $colour->id,
				'name'        => $colour->name,
				'description' => $colour->description,
				'image'       => $colour->image,
				'locked'      => $colour->locked
			);
			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode($list));
		}

		public function action_save()
		{

			if (!$this->user->can('Admin_Pet_Colour_Save'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour save');
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
				$colour = ORM::factory('Pet_Colour', $values['id']);
				$colour->values($values, array('name', 'description', 'locked', 'image'));
				$colour->save();

				$data = array(
					'action' => 'saved',
					'row'    => array(
						$colour->name,
						$colour->locked,
						$colour->id
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

			if (!$this->user->can('Admin_Pet_Colour_Delete'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour delete');
			}

			$this->view = NULL;
			$values = $this->request->post();

			$colour = ORM::factory('Pet_Colour', $values['id']);
			$colour->delete();

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('action' => 'deleted')));
		}
	}
