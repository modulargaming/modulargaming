<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User roles admin controller
 *
 * Manage user roles
 *
 * @package    ModularGaming/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Admin_Roles extends Abstract_Controller_Admin {
	protected $_root_node = 'User';
	protected $_node = 'Roles';

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Item_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}

		$this->_load_assets(Kohana::$config->load('assets.data_tables'));
		Assets::factory('body_admin')->js('admin.avatar.index', 'admin/roles.js');

		$this->view = new View_Admin_Roles_Index;
		;
	}

	public function action_paginate()
	{
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Role');

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'name', 'description'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $avatar)
			{
				$datatables->add_row(array(
						$avatar->name,
						$avatar->description,
						$avatar->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw HTTP_Exception::factory(500, 'error');
		}
	}

	public function action_retrieve()
	{
		$this->view = NULL;

		$item_id = $this->request->query('id');

		$item = ORM::factory('Role', $item_id);

		$list = array(
			'id'      => $item->id,
			'name'   => $item->name,
			'description' => $item->description,
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_save()
	{
		$this->view = NULL;
		$values = $this->request->post();

		if ($values['id'] == 0)
		{
			$values['id'] = NULL;
		}

		$id = $values['id'];

		$this->response->headers('Content-Type', 'application/json');

		try
		{
			$role = ORM::factory('Role', $values['id']);
			$role->values($values, array('name', 'description'));
			$role->save();

			$data = array(
				'action' => 'saved',
				'type'   => ($id == NULL) ? 'new' : 'update',
				'row'    => array(
					$role->name,
					$role->description,
					$role->id
				)
			);
			$this->response->body(json_encode($data));
		} catch (ORM_Validation_Exception $e)
		{
			$errors = array();

			$list = $e->errors('models');

			foreach ($list as $field => $er)
			{
				if ( ! is_array($er))
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
		$this->view = NULL;
		$values = $this->request->post();

		$item = ORM::factory('Role', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
