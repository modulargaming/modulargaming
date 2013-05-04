<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Admin_Avatars extends Abstract_Controller_Admin {

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Item_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}

		$this->view = new View_Admin_Avatar_Index;
		$this->_nav('user', 'avatar');
		$this->view->image = Kohana::$config->load('avatar.size');
	}

	public function action_paginate()
	{
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Avatar');

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'title', 'img', 'default'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $avatar)
			{
				$datatables->add_row(array(
						URL::base().'assets/img/avatars/'.$avatar->img,
						$avatar->title,
						$avatar->default,
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

		$item = ORM::factory('Avatar', $item_id);

		$list = array(
			'id'      => $item->id,
			'title'   => $item->title,
			'default' => $item->default,
			'img'     => URL::base().'assets/img/avatars/'.$item->img,
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
			$check = array('title', 'default');

			$file = array('status' => 'empty', 'msg' => '');

			if ( ! isset($values['img']))
			{
				$cfg = Kohana::$config->load('avatar.size');
				if ( ! Upload::image($_FILES['img']))
				{
					$file['status'] = 'error';
					$file['msg'] = 'The supplied image is not uploadable.';
				}
				elseif (file_exists(DOCROOT.'assets/img/avatars/'.$_FILES['img']['name']))
				{
					$file['status'] = 'error';
					$file['msg'] = 'There\'s already an image with the same filename';
				}
				elseif ( ! Upload::image($_FILES['img'], $cfg['width'], $cfg['heigth'], TRUE))
				{
					// not the right image dimensions
					$file = array('status' => 'error', 'msg' => 'You need to provide a valid image (size: :width x :heigth.', array(
						':width' => $cfg['width'], ':heigth' => $cfg['heigth']
					));
				}
				Upload::save($_FILES['img'], $_FILES['img']['name'], DOCROOT.'assets/img/avatars/');
				$values['img'] = $_FILES['img']['name'];
				$check[] = 'img';
			}

			if ( !isset($values['default']))
			{
				$values['default'] = FALSE;
			}
			$values['default'] = ($values['default'] == 'on');

			$avatar = ORM::factory('Avatar', $values['id']);
			$avatar->values($values, $check);
			$avatar->save();

			$data = array(
				'action' => 'saved',
				'type'   => ($id == NULL) ? 'new' : 'update',
				'row'    => array(
					URL::base().'assets/img/avatars/'.$avatar->img,
					$avatar->title,
					$avatar->default,
					$avatar->id
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

		$item = ORM::factory('Avatar', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
