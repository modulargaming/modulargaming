<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item type admin controller
 *
 * Manage site item types
 *
 * @package    MG/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Admin_Item_Shops extends Abstract_Controller_Admin {

	public function action_index()
	{
		if (!$this->user->can('Admin_Item_Shops_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops index');
		}

		$this->view = new View_Admin_Item_Shop;
		$this->view->image = Kohana::$config->load('items.npc.image');
		$this->_nav('items', 'shops');
	}

	public function action_paginate()
	{
		if (!$this->user->can('Admin_Item_Shops_Paginate'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops paginate');
		}

		if (DataTables::is_request())
		{
			$orm = ORM::factory('Shop');

			$paginate = Paginate::factory($orm)
				->columns(array('shop.id', 'title', 'status', 'stock_type'))
				->search_columns(array('title', 'status', 'stock_type'));

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
		if (!$this->user->can('Admin_Item_Shops_Retrieve'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops retrieve');
		}

		$this->view = NULL;

		$shop_id = $this->request->query('id');

		$shop = ORM::factory('Shop', $shop_id);

		$list = array(
			'id' => $shop->id,
			'title' => $shop->title,
			'npc_img' => $shop->npc_img,
			'npc_text' => $shop->npc_text,
			'stock_type' => $shop->stock_type,
			'stock_cap' => $shop->stock_cap,
			'status' => $shop->status,
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_save()
	{
		if (!$this->user->can('Admin_Item_Shops_Save'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops save');
		}

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

			//$base_dir = DOCROOT . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'items'
			// . DIRECTORY_SEPARATOR . 'shops' . DIRECTORY_SEPARATOR . 'npc' . DIRECTORY_SEPARATOR;
			//$values['npc_img'] = $base_dir . $values['npc_img'];
			
			$item = ORM::factory('Shop', $values['id']);
			if ($item->loaded())
			{
				$img = $item->npc_img;
				$item->values($values, array('title', 'status', 'npc_text', 'stock_type', 'stock_cap'));
			}
			else
			{
				$img = NULL;
				$values['npc_img'] = 'tmp';
				$item->values($values, array('title', 'status', 'npc_img', 'npc_text', 'stock_type', 'stock_cap'));
			}
			$item->save();

			$file = array('status' => 'empty', 'msg' => '');

			if (isset($_FILES['image']))
			{
				$image = $_FILES['image'];
				$cfg = Kohana::$config->load('items.npc.image');
				if ($img == NULL)
				{
					$img = $image['name'];
				}
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
					if ($id != NULL && $img != null && file_exists(DOCROOT . 'media/image/npc/shop/' . $img))
					{
						$grave_dir = DOCROOT . 'media/graveyard/npc/shop/';
						if (!is_dir($grave_dir))
						{
							mkdir($grave_dir, 0, true);
						}
						//move the previously stored item to the graveyard
						$new_name = Text::random('alnum', 4) . $img;
						copy(DOCROOT . 'media/image/npc/shop/' . $img, $grave_dir . $new_name);
						unlink(DOCROOT . 'media/image/npc/shop/' . $img);
						$msg = 'The old image has been moved to the graveyard and renamed to ' . $new_name;
					}

					if (!is_dir(DOCROOT . 'media/image/npc/shop/'))
					{
						mkdir(DOCROOT . 'media/image/npc/shop/', 0, true);
					}

					$up = Upload::save($image, $image['name'], DOCROOT . 'media/image/npc/shop/');
					// $up = Upload::save($image, DOCROOT . 'media/image/npc/shop/'.$image['name']);

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
				'type' => ($id == NULL) ? 'new' : 'update',
				'file' => $file,
				'row' => array(
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
		if (!$this->user->can('Admin_Item_Shops_Delete'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops delete');
		}

		$this->view = NULL;
		$values = $this->request->post();

		$item = ORM::factory('Shop', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}

	public function action_stock()
	{
		if (!$this->user->can('Admin_Item_Shops_Stock'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops stock');
		}

		$this->view = NULL;

		$shop_id = $this->request->query('id');

		$shop = ORM::factory('Shop', $shop_id);

		$items = array();

		switch ($shop->stock_type)
		{
			case 'steady':
				$inventory = ORM::factory('Shop_Inventory')
					->where('shop_id', '=', $shop_id)
					->find_all();

				if (count($inventory) > 0)
				{
					foreach ($inventory as $item)
					{
						$items[] = array(
							'id' => $item->id,
							'img' => $item->item->img(),
							'name' => $item->item->name,
							'price' => $item->price,
							'amount' => '-',
							'cap_amount' => '-',
							'last_restock' => '-'
						);
					}
				}
				break;
			case 'restock':
				$inventory = ORM::factory('Shop_Restock')
					->where('shop_id', '=', $shop_id)
					->find_all();
				if (count($inventory) > 0)
				{
					foreach ($inventory as $item)
					{
						$items[] = array(
							'id' => $item->id,
							'img' => $item->item->img(),
							'name' => $item->item->name,
							'price' => $item->min_price . ' - ' . $item->max_price,
							'amount' => $item->min_amount . ' - ' . $item->max_amount,
							'cap_amount' => $item->cap_amount,
							'frequency' => $item->frequency,
							'last_restock' => Date::format($item->next_restock - $item->frequency)
						);
					}
				}
				break;
		}
		$list = array(
			'stock_type' => $shop->stock_type,
			'stock_cap' => $shop->stock_cap,
			'total_amount' => count($items),
			'items' => $items,
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_stock_item()
	{
		if (!$this->user->can('Admin_Item_Shops_Stock_Item'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops stock item');
		}

		$shop_id = $this->request->post('shop_id');
		$item_id = $this->request->post('item_id');

		$shop = ORM::factory('Shop', $shop_id);

		switch ($shop->stock_type)
		{
			case 'steady':
				$item = ORM::factory('Shop_Inventory', $item_id);

				$data = array(
					'item_name' => $item->item->name,
					'min_price' => $item->price
				);
				break;
			case 'restock':
				$item = ORM::factory('Shop_Restock', $item_id);

				$data = array(
					'item_name' => $item->item->name,
					'frequency' => $item->frequency,
					'min_amount' => $item->min_amount,
					'max_amount' => $item->max_amount,
					'cap_amount' => $item->cap_amount,
					'min_price' => $item->min_price,
					'max_price' => $item->max_price
				);
				break;
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($data));
	}

	public function action_stock_save()
	{
		if (!$this->user->can('Admin_Item_Shops_Stock_Save'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item shops stock save');
		}

		$shop_id = $this->request->post('shop_id');
		$item_id = ($this->request->post('item_id') == 0) ? null : $this->request->post('item_id');
		$values = $this->request->post();
		$state = 'edit';

		if ($item_id == null)
		{
			$i = ORM::factory('Item')
				->where('item.name', '=', $values['item_name'])
				->find();
			$item_id = $i->id;
			$state = 'new';
		}
		$shop = ORM::factory('Shop', $shop_id);

		try
		{
			switch ($shop->stock_type)
			{
				case 'steady':
					$item = ORM::factory('Shop_Inventory');

					if ($state == 'edit')
					{
						$item = $item->where('item_id', '=', $item_id)
							->where('shop_id', '=', $shop_id)
							->find();
					}
					else
					{
						$item->item_id = $item_id;
						$item->shop_id = $shop_id;
					}


					$item->price = $values['min_price'];
					$item->stock = $values['min_amount'];
					$item->save();
					break;
				case 'restock':
					$item = ORM::factory('Shop_Restock');

					if ($state == 'edit')
					{
						$item = $item->where('item_id', '=', $item_id)
							->where('shop_id', '=', $shop_id)
							->find();
					}
					else
					{
						$item->item_id = $item_id;
						$item->shop_id = $shop_id;
					}

					$item->values($values, array('frequency', 'min_amount', 'max_amount', 'cap_amount', 'min_price', 'max_price'));
					$item->save();
					break;
			}
			$data = array(
				'status' => 'saved'
			);
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

			$data = array('status' => 'error', 'errors' => $errors);
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($data));
	}
}
