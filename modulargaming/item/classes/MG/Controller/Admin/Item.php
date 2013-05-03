<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item admin controller
 *
 * Manage site items
 *
 * @package    MG/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Controller_Admin_Item extends Abstract_Controller_Admin {

	public function action_index()
	{
		if (!$this->user->can('Admin_Item_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}

		$types = ORM::factory('Item_Type')
			->find_all();

		$this->view = new View_Admin_Item_Index;
		$this->_nav('items', 'index');
		$this->view->item_types = $types->as_array();

		$commands = Item::list_commands();
		$input_c = array();
		$menu_c = array(
			array('name' => 'General', 'commands' => array()),
			array('name' => 'Pet', 'commands' => array()),
		);
		$def_c = array();

		foreach ($commands as $cmd)
		{
			$name = str_replace(DIRECTORY_SEPARATOR, '_', $cmd);
			$class = 'Item_Command_' . $name;
			$command = new $class;

			if ($command->is_default() == FALSE)
			{
				$struct = explode('_', $name);
				$admin = $command->build_admin($name);
				$input_c[] = array('title' => $admin['title'], 'fields' => $admin['fields']);
				$def_c[] = array(
					'name' => $name,
					'multiple' => $admin['multiple'],
					'pets' => $admin['pets'],
					'search' => $admin['search'],
					'only' => (!$command->allow_more)
				);
				$loc = (in_array($struct[0], array('General', 'User'))) ? 0 : 1;
				$menu_c[$loc]['commands'][] = array(
					'name' => $struct[1],
					'cmd' => $name
				);
			}
		}

		$this->view->input_commands = $input_c;
		$this->view->menu_commands = $menu_c;
		$this->view->command_definitions = $def_c;
		$this->view->image = Kohana::$config->load('items.image');
	}

	public function action_search()
	{
		if (!$this->user->can('Admin_Item_Search'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item search');
		}


		$this->view = NULL;
		$type = $this->request->query('type');

		$name = $this->request->query('item');
		$property = 'name';

		if ($type == 'item')
		{
			$items = ORM::factory('Item')
				->where('item.name', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'item_type')
		{
			$items = ORM::factory('Item_Type')
				->where('item_type.name', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'user')
		{
			$property = 'username';

			$items = ORM::factory('User')
				->where('username', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'recipe')
		{
			$items = ORM::factory('Item_Recipe')
				->where('item_recipe.name', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'pet-specie')
		{
			$items = ORM::factory('Pet_Specie')
				->where('pet_specie.name', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'pet-color')
		{
			$items = ORM::factory('Pet_Colour')
				->where('pet_colour.name', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else if ($type == 'avatar')
		{
			$property = 'title';

			$items = ORM::factory('Avatar')
				->where('avatar.title', 'LIKE', '%' . $name . '%')
				->find_all();
		}
		else
		{
			$items = array();
		}

		$list = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$list[] = $item->{$property};
			}
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_paginate()
	{

		if (!$this->user->can('Admin_Item_Paginate'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item paginate');
		}

		if (DataTables::is_request())
		{
			$orm = ORM::factory('Item');

			$paginate = Paginate::factory($orm)
				->columns(array('item.id', 'item.name', 'image', 'status', 'type.name'))
				->search_columns(array('item.name', 'status', 'type.name'));

			$datatables = DataTables::factory($paginate)->execute();

			foreach ($datatables->result() as $item)
			{
				$datatables->add_row(array(
						$item->img(),
						$item->name,
						$item->status,
						$item->type->name,
						$item->id
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

		if (!$this->user->can('Admin_Item_Retrieve'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item retrieve');
		}

		$this->view = NULL;

		$item_id = $this->request->query('id');

		$item = ORM::factory('Item', $item_id);

		$list = array(
			'id' => $item->id,
			'name' => $item->name,
			'status' => $item->status,
			'image' => $item->img(),
			'description' => $item->description,
			'unique' => $item->unique,
			'transferable' => $item->transferable,
			'type_id' => $item->type_id,
			'commands' => $item->commands
		);
		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_save()
	{

		if (!$this->user->can('Admin_Item_Save'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item save');
		}

		$values = $this->request->post();
		$cfg = Kohana::$config->load('items.image');
		$this->view = NULL;

		if ($values['id'] == 0)
		{
			$values['id'] = NULL;
		}

		$id = $values['id'];

		$this->response->headers('Content-Type', 'application/json');

		//get the item
		$item = ORM::factory('Item', $values['id']);

		$file = array('status' => 'empty', 'msg' => '');
		$TMP = NULL;
		$upload = NULL;

		if (isset($_FILES['image']))
		{
			$image = $_FILES['image'];

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
			elseif (!Upload::type($image, $cfg['format']))
			{
				//not the right image type
				$file = array('status' => 'error', 'msg' => 'You need to provide a valid image (type: :type).', array(
					':type' => implode(',', $cfg['format'])));
			}
			else
			{
				//check if the temp dir exists
				if (!file_exists($cfg['tmp_dir']))
				{
					mkdir($cfg['tmp_dir']);
				}

				//save it temporarily
				$upload = Image::factory($image['tmp_name'])
					->save($cfg['tmp_dir'].$image['name'].Text::random().'.png');

				$TMP = array('upload' => $upload, 'name' => $image['name']);

				if ($TMP['upload'] != FALSE)
				{
					$file['status'] = 'temp';
				}
				else
				{
					$file = array('status' => 'error', 'msg' => 'There was an error uploading your file.');
				}
			}
		}

		if ($file['status'] == 'temp' || $file['status'] == 'empty')
		{
			try
			{
				$data = array();

				$type = ORM::factory('Item_Type', $values['type_id']);

				$base_dir = DOCROOT . 'media' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR;

				//if we're just changing the item type we'll have to move it a different dir
				if ($type->id != $item->type_id && $file['status'] == 'empty')
				{
					$TMP['upload'] = $base_dir . $item->type->img_dir . $item->img;
					$TMP['name'] = $item->img;
				}

				//move the file to the correct dir if it's possible
				$new_loc = $base_dir . $type->img_dir . $TMP['name'];

				//check if the dir exists
				if (!file_exists($base_dir . $type->img_dir))
				{
					mkdir($base_dir . $type->img_dir);
				}

				if (($file['status'] == 'empty' && $TMP != null) && file_exists($new_loc))
				{
					$file = array('status' => 'error', 'msg' => 'That filename already exists');
					$data['type'] = 'error';
					$data['errors'] = array();
				}
				else
				{
					//if commands are set parse them
					if (isset($values['commands']))
					{
						$values['commands'] = Item::parse_commands($values['commands']);
					}

					//attempt to save the item
					if ($TMP != null)
					{
						$values['image'] = $TMP['name'];
						$item->values($values, array('name', 'status', 'image', 'description', 'unique', 'transferable', 'type_id', 'commands'));
						$item->save();

						//if it's saved move the file to the new location
						if ($item->saved())
						{
							//move the uploaded file to the correct place with the correct name
							if($upload != null)
							{
								$upload = Image::factory($image['tmp_name'])
                                        			->save($new_loc);
							}
							//otherwise move the file to the new dir
							else
							{
								copy($TMP['upload'], $new_loc);
							}

							$file['status'] = 'success';
						}
					}
					else
					{
						$item->values($values, array('name', 'status', 'description', 'unique', 'transferable', 'type_id', 'commands'));
						$item->save();
					}

					$data['row'] = array(
						$item->img(),
						$item->name,
						$item->status,
						$item->type->name,
						$item->id,
					);
					$data['action'] = 'saved';
				}

				$data['type'] = ($id == NULL) ? 'new' : 'update';
				$data['file'] = $file;

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
		else
		{
			$this->response->body(json_encode(array('action' => 'error', 'errors' => array('upload_file' => array('Error uploading your file')))));
		}
	}

	public function action_delete()
	{
		if (!$this->user->can('Admin_Item_Delete'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item delete');
		}

		$this->view = NULL;
		$values = $this->request->post();

		$item = ORM::factory('Item', $values['id']);
		$item->delete();

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}

	public function action_gift()
	{

		if (!$this->user->can('Admin_Item_Gift'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item gift');
		}

		$this->view = NULL;

		//gift the item
		$item = Item::factory($this->request->post('id'));

		$user = ORM::factory('User')
			->where('username', '=', $this->request->post('username'))
			->find();

		try
		{
			$log = $item->to_user($user, 'admin.' . $this->user->username, $this->request->post('amount'));

			//notify the user
			$log->notify($user, 'You received :item_name!', array(':item_name' => $item->item()->name));

			$list = array('action' => 'success');
		} catch (Item_Exception $e)
		{
			$list = array('action' => 'error', 'errors' => (array)$e->getMessage());
		}

		//return response
		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}
}
