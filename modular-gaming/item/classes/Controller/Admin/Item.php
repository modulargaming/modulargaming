<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Item extends Abstract_Controller_Admin {

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Item_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}
		
		
		$this->_load_assets(Kohana::$config->load('assets.admin_item.list'));
		
		$types = ORM::factory('Item_Type')
			->find_all();
		
		//@todo limit when pagination arrives
		$items = ORM::factory('Item')
			->find_all();

		$this->view = new View_Admin_Item_Index;
		$this->_nav('items', 'index');
		$this->view->item_types = $types->as_array();
		$this->view->items = $items;

		$commands = Item::list_commands();
		$input_c = array();
		$menu_c = array(
			array('name' => 'General', 'commands' => array()),
			array('name' => 'Pet', 'commands' => array()),
		);
		$def_c = array();
		
		foreach($commands as $cmd) {
			$name = str_replace('/', '_', $cmd['name']);
			$class = 'Item_Command_'.$name;
			$command = new $class;
			
			if($command->is_default() == false)
			{
				$struct = explode('_', $name);
				$admin = $command->build_admin($name);
				$input_c[] = array('title' => $admin['title'], 'fields' => $admin['fields']);
				$def_c[] = array(
					'name' => $name,
					'multiple' => $admin['multiple'],
					'pets' => $admin['pets'],
					'search' => $admin['search']
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
				->where('name', 'LIKE', '%'.$item_name.'%')
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
			->where('name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		else if($type == 'pet-color') 
		{
			$item_name = $this->request->query('name');
			$property = 'name';
		
			$items = ORM::factory('Pet_Colour')
			->where('name', 'LIKE', '%'.$item_name.'%')
			->find_all();
		}
		
		$list = array();
		
		foreach($items as $item) {
			$list[] = $item->{$property};
		}
		
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	 
	public function action_retrieve() {
		$this->view = null;
	
		$item_id = $this->request->query('id');
		
		if($item_id == null) {
			$item = ORM::factory('Item')
				->where('item.name', '=', $this->request->query('name'))
				->find();
		}
		else
			$item = ORM::factory('Item', $item_id);
	
		$list = array(
			'id' => $item->id,
			'name' => $item->name,
			'status' => $item->status,
			'image' => $item->image,
			'description' => $item->description,
			'unique' => $item->unique,
			'transferable' => $item->transferable,
			'type_id' => $item->type_id,
			'commands' => json_decode($item->commands)		
		);
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_save(){
		$values = $this->request->post();
		$this->view = null;
		
		if($values['id'] == 0)
			$values['id'] = null;
		
		$this->response->headers('Content-Type','application/json');
		
		try {
			$cmd = $values['commands'];
			$commands = array();
			
			foreach($cmd as $k => $c) {
				$commands[] = array('name' => $k, 'param' => $c);
			}
			
			$values['commands'] = json_encode($commands);
			
			$item = ORM::factory('Item', $values['id']);
			$item->values($values, array('name', 'status', 'image', 'description', 'unique', 'transferable', 'type_id', 'commands'));
			$item->save();
			
			$data = array(
				'action' => 'saved',
				'row' => array(
					'id' => $item->id,
					'img' => '<img src="'.URL::base().$item->img().'" />',
					'name' => $item->name,
					'type' => $item->type->name
				)
			);
			$this->response->body(json_encode($data));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = array();
			
			$list = $e->errors('models');
			
			foreach($list as $field => $er){
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
			$item->to_user($user, $this->request->post('amount'));
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
