<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Item extends Abstract_Controller_Admin {
	protected $_nav = array(
			'index' => array('link' => 'item', 'title' => 'Items', 'active' => false),	
			'types' => array('link' => 'item/types', 'title' => 'Types', 'active' => false),
			'recipes' =>	array('link' => 'item/recipes', 'title' => 'Recipes', 'active' => false),
		);
	
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
		$this->view->item_types = $types->as_array();
		$this->view->items = $items;

		$this->view->js_file = 'type';
	}
	
	public function action_types()
	{
		if ( ! $this->user->can('Admin_Item_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}
		$this->_load_assets(Kohana::$config->load('assets.admin_item.type'));
	
		$types = ORM::factory('Item_Type')
		->find_all();
	
		$this->view = new View_Admin_Item_Type;
		$this->view->types = $types->as_array();
	}
	
	public function action_recipes()
	{
		$this->view = new View_Admin_Item_Recipe;
	
		if ( ! $this->user->can('Admin_Item_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin item index');
		}
		
		$this->_load_assets(Kohana::$config->load('assets.admin_item.recipe'));
	
	
		$types = ORM::factory('Item_Recipe')
		->find_all();
	
		$this->view->recipes = $types;
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
		else if($type == 'item_type') {
			$item_name = $this->request->query('item');
			$property = 'name';
			
			$items = ORM::factory('Item_Type')
				->where('name', 'LIKE', '%'.$item_name.'%')
				->find_all();
		}
		else if($type == 'user') {
			$item_name = $this->request->query('username');
			$property = 'username';
			
			$items = ORM::factory('User')
				->where('username', 'LIKE', '%'.$item_name.'%')
				->find_all();
		}
		else if($type == 'recipe') {
			$item_name = $this->request->query('name');
			$property = 'name';
				
			$items = ORM::factory('Item_Recipe')
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
			'type_id' => $item->type_id		
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
			$item = ORM::factory('Item', $values['id']);
			$item->values($values, array('name', 'status', 'image', 'description', 'unique', 'transferable', 'type_id'));
			$item->save();
			
			$this->response->body(json_encode(array('action' => 'saved')));
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
	
	public function action_retrieve_type() {
		$this->view = null;
	
		$item_id = $this->request->query('id');
	
		if($item_id == null) {
			$item = ORM::factory('Item_Type')
			->where('item.name', '=', $this->request->query('name'))
			->find();
		}
		else
			$item = ORM::factory('Item_Type', $item_id);
	
		$list = array(
				'id' => $item->id,
				'name' => $item->name,
				'action' => $item->action,
				'default_command' => $item->default_command,
				'img_dir' => $item->img_dir,
				'load_pet_list' => $item->load_pet_list,
		);
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_save_type(){
		$this->view = null;
		$values = $this->request->post();
	
		if($values['id'] == 0)
			$values['id'] = null;
	
		$this->response->headers('Content-Type','application/json');
	
		try {
			$item = ORM::factory('Item_Type', $values['id']);
			$item->values($values, array('name', 'status', 'action', 'default_command', 'img_dir', 'load_pet_list'));
			$item->save();
				
			$this->response->body(json_encode(array('action' => 'saved')));
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
	
	public function action_delete_type(){
		$this->view = null;
		$values = $this->request->post();
	
		$item = ORM::factory('Item_type', $values['id']);
		$item->delete();
	
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
	
	public function action_recipe_retrieve() {
		$this->view = null;
	
		$item_id = $this->request->query('id');
	
		$item = ORM::factory('Item_Recipe', $item_id);
	
		$list = array(
				'id' => $item->id,
				'name' => $item->name,
				'description' => $item->description,
				'crafted_item' => $item->item->name,
		);
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_recipe_save(){
		$this->view = null;
		$values = $this->request->post();
	
		if($values['id'] == 0)
			$values['id'] = null;
	
		$this->response->headers('Content-Type','application/json');
	
		try {
			$crafted = ORM::factory('Item')
				->where('item.name', '=', $values['crafted_item'])
				->find();
			
			if($crafted->loaded()) {
				$values['crafted_item_id'] = $crafted->id;
				
				$item = ORM::factory('Item_Recipe', $values['id']);
				$item->values($values, array('name', 'description', 'crafted_item_id'));
				$item->save();
		
				$this->response->body(json_encode(array('action' => 'saved')));
			}
			else {
				return $this->response->body(json_encode(array('action' => 'error', 'errors' => array(array('field' => 'crafted_item', 'msg' => array('This item does not seem to exist.'))))));
			}
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
	
	public function action_recipe_delete(){
		$this->view = null;
		$values = $this->request->post();
	
		$item = ORM::factory('Item_Recipe', $values['id']);
		$item->delete();
	
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
	
	public function action_gift(){
		$this->view = null;
	
		//gift the item
		$item = $this->request->post('id');
		$user = ORM::factory('User')
		->where('username', '=', $this->request->post('username'))
		->find();
		$user_item = ORM::factory('User_Item')->recieve($user, $item, $this->request->post('amount'));
	
		if(!is_array($user_item))
			$list = array('action' => 'success');
		else {
			$list = array('action' => 'error', 'errors' => (array) $user_item);
		}
		//return response
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
}
