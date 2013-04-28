<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item Avatar admin controller
 *
 * Manage site item types
 *
 * @package    ModularGaming/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Admin_Logs extends Abstract_Controller_Admin {
	protected $_root_node = 'User';
	protected $_node = 'Logs';

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Log'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}

		$this->_load_assets(Kohana::$config->load('assets.data_tables'));
		Assets::factory('body_admin')->js('admin.logs.index', 'admin/logs.js');

		$this->view = new View_Admin_Logs;

		$this->view->image = Kohana::$config->load('avatar.size');
		;
	}

	public function action_paginate()
	{
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Log');

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'user_id', 'location', 'alias', 'time', 'type'))
				->search_columns(array('user.username', 'alias', 'location', 'type'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $log)
			{
				$datatables->add_row(array(
						$log->type,
						$log->alias,
						$log->user->username,
						$log->location,
						$log->time,
						$log->id
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

		$log_id = $this->request->query('id');

		$log = ORM::factory('Log', $log_id);

		$list = array(
			'id'      => $log->id,
			'user_id'   => $log->user_id,
			'username' => $log->user->username,
			'type' => $log->type,
			'alias' => $log->alias,
			'location' => $log->location,
			'client' => array(
				'agent' => $log->agent,
				'ip' => $log->ip
			),
			'time' => date('l jS F G:i:s', $log->time),
			'message' => __($log->message, $log->params),
			'params' => $log->params
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}
}
