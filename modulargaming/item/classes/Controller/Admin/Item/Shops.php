<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item type admin controller
 *
 * Manage site item types
 *
 * @package    ModularGaming/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Admin_Item_Shops extends Abstract_Controller_Admin {

	public function action_index()
	{
		if (!$this->user->can('Admin_Item_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}

		$this->_load_assets(Kohana::$config->load('assets.data_tables'));
		$this->_load_assets(Kohana::$config->load('assets.admin_item.shop'));

		$this->view = new View_Admin_Item_Shop;
		$this->view->image = Kohana::$config->load('items.npc.image');
		$this->_nav('items', 'shops');
	}

	public function action_paginate()
	{
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Shop');

			$paginate = Paginate::factory($orm)
				->columns(array('id', 'title', 'status', 'stock_type'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $shop)
			{
				$datatables->add_row(array(
						$shop->title,
						$shop->status,
						$shop->stock_type,
						$shop->id
					)
				);
			}

			$datatables->render($this->response);
		}
		else
		{
			throw new HTTP_Exception_500();
		}
	}

	public function action_retrieve()
	{
		$this->view = NULL;

		$shop_id = $this->request->query('id');

		$shop = ORM::factory('Shop', $shop_id);

		$list = array(
			'id'              => $shop->id,
			'title'            => $shop->title,
			'npc_img'          => $shop->npc_img,
			'npc_text' => $shop->npc_text,
			'stock_type'         => $shop->stock_type,
			'stock_cap'         => $shop->stock_cap,
			'status'         => $shop->status,
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
			$values['npc_img'] = 'tmp';

			$item = ORM::factory('Shop', $values['id']);

			$img = ($item->loaded()) ? $item->npc_img : null;

			$item->values($values, array('title', 'status', 'npc_img', 'npc_text', 'stock_type', 'stock_cap'));
			$item->save();

			$file = array('status' => 'empty', 'msg' => '');

			if (isset($_FILES['image']))
			{
				$image = $_FILES['image'];
				$cfg = Kohana::$config->load('items.npc.image');

				if (!Upload::valid($image))
				{
					//error not valid upload
					$file = array('status' => 'error', 'msg' => 'You did not provide a valid file to upload.');
				}
				else if (!Upload::image($image, $cfg['width'], $cfg['height'], TRUE))
				{
					//not the right image dimensions
					$file = array('status' => 'error', 'msg' => 'You need to provide a valid image (size: :width x :height.', array(
						':width' => $cfg['width'], ':height' => $cfg['height']
					));
				}
				else
				{
					$msg = '';
					if ($id != NULL && $img != null && file_exists(DOCROOT . 'assets/img/npc/shop/' . $img))
					{
						//move the previously stored item to the graveyard
						$new_name = Text::random('alnum', 4) . $img;
						copy(DOCROOT . 'assets/img/npc/shop/' . $img, DOCROOT . 'assets/graveyard/npc/shop/' . $new_name);
						unlink(DOCROOT . 'assets/img/npc/shop/' . $img);
						$msg = 'The old image has been moved to the graveyard and renamed to ' . $new_name;
					}

					$up = Upload::save($image, $image['name'], DOCROOT . 'assets/img/npc/shop/');

					if ($up != FALSE)
					{
						$file['status'] = 'success';
						$file['msg'] = 'You\'ve successfully uploaded your item image';

						if (!empty($msg))
						{
							$file['msg'] .= '<br />' . $msg;
						}

						$item->npc_img = $image['name'];
						$item->save();
					}
					else
					{
						$file = array('status' => 'error', 'msg' => 'There was an error uploading your file.');
					}
				}
			}

			$data = array(
				'action' => 'saved',
				'type'   => ($id == NULL) ? 'new' : 'update',
				'file'   => $file,
				'row'    => array(
					$item->title,
					$item->stock_type,
					$item->status,
					$item->id
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
		$this->view = NULL;
		$values = $this->request->post();

		$item = ORM::factory('Shop', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
