<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_User_Role extends Abstract_Controller_Admin {

		public function action_index()
		{

			if (!$this->user->can('Admin_User_Role_Index'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin user role index');
			}

			$roles = ORM::factory('Role')
				->find_all();

			$this->view = new View_Admin_User_Role_Index;
			$this->_nav('user', 'role');
			$this->view->roles = $roles->as_array();
		}

		public function action_paginate() {

			if (!$this->user->can('Admin_User_Role_Paginate'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin user role paginate');
			}


			if (DataTables::is_request())
			{
				$orm = ORM::factory('Role');

				$paginate = Paginate::factory($orm)
					->columns(array('id', 'name', 'description'));

				$datatables = DataTables::factory($paginate)->execute();

				foreach ($datatables->result() as $role)
				{
					$datatables->add_row(array (
							$role->name,
							$role->description,
							$role->id
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

			if (!$this->user->can('Admin_User_Role_Retrieve'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin user role retrieve');
			}

			$this->view = NULL;

			$role_id = $this->request->query('id');

			if ($role_id == NULL)
			{
				$role = ORM::factory('Role')
					->where('role.name', '=', $this->request->query('name'))
					->find();
			}
			else
			{
				$role = ORM::factory('Role', $role_id);
			}

			$list = array(
				'id'          => $role->id,
				'name'        => $role->name,
				'description' => $role->description,
			);
			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode($list));
		}

		public function action_save()
		{

			if (!$this->user->can('Admin_User_Role_Save'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin user role save');
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
				$role = ORM::factory('Role', $values['id']);
				$role->values($values, array('name', 'description'));
				$role->save();

				$data = array(
					'action' => 'saved',
					'row'    => array(
						$role->id,
						$role->name,
						$role->description

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

			if (!$this->user->can('Admin_User_Role_Delete'))
			{
				throw HTTP_Exception::factory('403', 'Permission denied to view admin user role delete');
			}

			$this->view = NULL;
			$values = $this->request->post();

			$role = ORM::factory('Role', $values['id']);
			$role->delete();

			$this->response->headers('Content-Type', 'application/json');
			$this->response->body(json_encode(array('action' => 'deleted')));
		}
	}
