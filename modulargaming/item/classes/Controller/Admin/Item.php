<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item admin controller
 * 
 * Manage site items
 *
 * @package    ModularGaming/Items
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Controller_Admin_Item extends Abstract_Controller_Admin {

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Item_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}
		
		$this->_load_assets(Kohana::$config->load('assets.data_tables'));
		$this->_load_assets(Kohana::$config->load('assets.upload'));
		$this->_load_assets(Kohana::$config->load('assets.admin_item.list'));
		
		$types = ORM::factory('Item_Type')
			->find_all();

		$this->view = new View_Admin_Item_Index;
		$this->_nav('items', 'index');
		$this->view->item_types = $types->as_array();

		$commands = Item::list_commands();
		$input_c = array();
		$menu_c = array (
			array('name' => 'General', 'commands' => array()),
			array('name' => 'Pet', 'commands' => array()),
		);
		$def_c = array();
		
		foreach($commands as $cmd) {
			$name = str_replace(DIRECTORY_SEPARATOR, '_', $cmd);
			$class = 'Item_Command_'.$name;
			$command = new $class;
			
			if($command->is_default() == false)
			{
				$struct = explode('_', $name);
				$admin = $command->build_admin($name);
				$input_c[] = array('title' => $admin['title'], 'fields' => $admin['fields']);
				$def_c[] = array (
					'name' => $name,
					'multiple' => $admin['multiple'],
					'pets' => $admin['pets'],
					'search' => $admin['search'],
					'only' => (!$command->allow_more)
				);
				$loc = (in_array($struct[0], array('General', 'User'))) ? 0 : 1;
				$menu_c[$loc]['commands'][] = array (
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
	
	public function action_search() {
		$this->view = null;
		$type = $this->request->query('type');
		
		if($type == 'item') {
			$item_name = $this->request->query('item');
			$property = 'name';
			
			$items = ORM::factory('Item')
				->where('item.name', 'LIKE', '%'.$item_name.'%')
				->find_all();
		}
		else if($type == 'item_type') 
		{
			$item_name = $this->request->query('item');
			$property = 'name';
			
			$items = ORM::factory('Item_Type')
				->where('item_type.name', 'LIKE', '%'.$item_name.'%')
				->find_all();
		}
		else if($type == 'user') 
		{
			$item_name = $this->request->query('username');
			$property = 'username';
			
			$items = ORM::factory('User')
				->where('username', 'LIKE', '%'.$item_name.'%')
				->find_all();
		}
		else if($type == 'recipe') 
		{
			$item_name = $this->request->query('name');
			$property = 'name';
				
			$items = ORM::factory('Item_Recipe')
			->where('item_recipe.name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		else if($type == 'pet-specie') 
		{
			$item_name = $this->request->query('name');
			$property = 'name';
		
			$items = ORM::factory('Pet_Specie')
			->where('pet_specie.name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		else if($type == 'pet-color') 
		{
			$item_name = $this->request->query('name');
			$property = 'name';
		
			$items = ORM::factory('Pet_Colour')
			->where('pet_colour.name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		
		$list = array();
		
		foreach($items as $item) {
			$list[] = $item->{$property};
		}
		
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_paginate() {
		if (DataTables::is_request())
		{
			$orm = ORM::factory('Item');
	
			$paginate = Paginate::factory($orm)
			->columns(array('id', 'name', 'image', 'status', 'type'));
	
			$datatables = DataTables::factory($paginate)->execute();
	
			foreach ($datatables->result() as $item)
			{
				$datatables->add_row(array (
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
			throw new HTTP_Exception_500();
	}
	 
	public function action_retrieve() {
		$this->view = null;
	
		$item_id = $this->request->query('id');

		$item = ORM::factory('Item', $item_id);
	
		$list = array (
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
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_save(){
		$values = $this->request->post();
		$this->view = null;
		
		if($values['id'] == 0)
			$values['id'] = null;
		
		$id = $values['id'];
		
		$this->response->headers('Content-Type','application/json');

		try {			
			if(isset($values['commands']))
				$values['commands'] = Item::parse_commands($values['commands']);
			
			$item = ORM::factory('Item', $values['id']);
			
			$img = $item->image;
			$dir = $item->type->img_dir;
			
			$values['image'] = (isset($_FILES['image'])) ? 'tmp' : $img;
			$item->values($values, array('name', 'status', 'image', 'description', 'unique', 'transferable', 'type_id', 'commands'));
			$item->save();
			
			$file = array('status' => 'empty', 'msg' => '');
			
			if(isset($_FILES['image']))
			{
				$image = $_FILES['image'];
				$cfg = Kohana::$config->load('items.image');
				
				if(!Upload::valid($image))
				{
					//error not valid upload
					$file = array('status' => 'error', 'msg' => 'You did not provide a valid file to upload.');
				}
				else if(!Upload::image($image, $cfg['width'], $cfg['heigth'], true))
				{
					//not the right image dimensions
					$file = array('status' => 'error', 'msg' => 'You need to provide a valid image (size: :width x :heigth.', array(
						':width' => $cfg['width'], ':heigth' => $cfg['heigth']
					));
				}
				else 
				{
					$msg = '';
					if($id != null && !empty($img) && file_exists(DOCROOT.'assets/img/items/'.$dir.$img))
					{
						//move the previously stored item to the graveyard
						$new_name = Text::random('alnum', 4).$img;
						copy(DOCROOT.'assets/img/items/'.$dir.$img, DOCROOT.'assets/graveyard/items/'.$new_name);
						unlink(DOCROOT.'assets/img/items/'.$dir.$img);
						$msg = 'The old image has been moved to the graveyard and renamed to '.$new_name;
					}
					
					$up = Upload::save($image, $image['name'], DOCROOT.'assets/img/items/'.$item->type->img_dir);
					
					if($up != false)
					{
						$file['status'] = 'success';
						$file['msg'] = 'You\'ve successfully uploaded your item image';
						
						if(!empty($msg))
							$file['msg'] .= '<br />'.$msg;
						
						$item->image = $image['name'];
						$item->save();
					}
					else 
					{
						$file = array('status' => 'error', 'msg' => 'There was an error uploading your file.');
					}
				}
			}
			else if($dir != $item->type->img_dir && file_exists(DOCROOT.'assets/img/items/'.$dir.$img))
			{
				//item type changed, move the item image
				copy(DOCROOT.'assets/img/items/'.$dir.$item->image, DOCROOT.'assets/img/items/'.$item->type->img_dir.$item->image);
				unlink(DOCROOT.'assets/img/items/'.$dir.$item->image);
			}
			
			$data = array (
				'action' => 'saved',
				'type' => ($id == null) ? 'new' : 'update',
				'file' => $file,
				'row' => array (
					$item->img(),
					$item->name,
					$item->status,
					$item->type->name,
					$item->id,
				)
			);
			$this->response->body(json_encode($data));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = array();
			
			$list = $e->errors('models');
			
			foreach($list as $field => $er) {
				if(!is_array($er))
					$er = array($er);
				
				$errors[] = array('field' => $field, 'msg' => $er);
			}
			
			$this->response->body(json_encode(array('action' => 'error', 'errors' => $errors)));
		}
	}
	
	public function action_delete(){
		$this->view = null;
		$values = $this->request->post();
	
		$item = ORM::factory('Item', $values['id']);
		$item->delete();
				
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}

	public function action_gift(){
		$this->view = null;
	
		//gift the item
		$item = Item::factory($this->request->post('id'));
		
		$user = ORM::factory('User')
		->where('username', '=', $this->request->post('username'))
		->find();
		
		try {
			$item->to_user($user, 'admin.'.$this->user->username, $this->request->post('amount'));
			$list = array('action' => 'success');
		}
		catch(Item_Exception $e) {
			$list = array('action' => 'error', 'errors' => (array) $e->errors());
		}
		
		//return response
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
}
