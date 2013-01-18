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

		$commands = Item::list_commands();
		$input_c = array();
		
		foreach($commands as $cmd) {
			$name = 'Item_Command_'.$cmd['name'];
			$command = new $name;
			
			if($command->is_default() == false)
			{
				$input_c[] = $command->build_form();
			}
		}
		
		$this->view->input_commands = $input_c;
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
		
		$commands = Item::list_commands();
		$list_c = array();
		
		foreach($commands as $cmd) {
			$name = 'Item_Command_'.$cmd['name'];
			$command = new $name;
			
			if($command->is_default() == false)
			{
				$list_c[] = array(
					'name' => str_replace('_', ' - ', $cmd['name']),
					'value' => $cmd['name']		
				);
			}
		}
		$this->view->commands = $list_c;
		
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
			->where('item_recipe.name', 'LIKE', '%'.$item_name.'%')
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
			
			$data = array(
				'action' => 'saved',
				'row' => array(
					'id' => $item->id,
					'name' => $item->name		
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
		
		if($item_id != null)
			$item = ORM::factory('Item_Recipe', $item_id);
		else 
			$item = ORM::factory('Item_Recipe')->where('item_recipe.name', '=', $this->request->query('name'))->find();
		
		$materials = $item->materials->find_all();
		$ingredients = array();
		
		foreach($materials as $ingredient) {
			$ingredients[] = array(
				'id' => $ingredient->id,
				'name' => $ingredient->item->name,
				'amount' => $ingredient->amount		
			);
		}
		
		$list = array(
				'id' => $item->id,
				'name' => $item->name,
				'description' => $item->description,
				'crafted_item' => $item->item->name,
				'materials' => $ingredients
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
			//validate crafted item
			$crafted = ORM::factory('Item')
				->where('item.name', '=', $values['crafted_item'])
				->find();
			
			
			if($crafted->loaded()) {
				//validate item materials
				$materials = $this->request->post('materials');
				$values['materials'] = $materials;
				
				$mat_fail = false;
			
				if(count($materials) > 0) {
					foreach($materials as $index => $material){
						$mat = ORM::factory('Item')
							->where('item.name', '=', $material['name'])
							->find();
						if(!$mat->loaded()) {
							$mat_fail = $material['name'] . ' does not exist';
							break;
						}
						else if(!Valid::digit($material['amount'])) {
							$mat_fail = $material['name'] . '\'s amount should be a number';
							break;
						}
						else 
							$materials[$index]['item'] = $mat->id;
					}
				}
				if($mat_fail == false) {
					$values['crafted_item_id'] = $crafted->id;
					
					$item = ORM::factory('Item_Recipe', $values['id']);
					$item->values($values, array('name', 'description', 'crafted_item_id'));
					$item->save();
					
					if(count($materials) > 0) {
						//if we're updating delete old data
						if($values['id'] != null) {
							foreach($item->materials->find_all() as $mat)
								$mat->delete();
						}
						
						foreach($materials as $key => $ingredient) {
							$mat = ORM::factory('Item_Recipe_Material');
							$mat->item_id = $ingredient['item'];
							$mat->amount = $ingredient['amount'];
							$mat->item_recipe_id = $item->id;
							$mat->save();
						}
					}
					$data = array(
						'action' => 'saved',
						'row' => array(
							'id' => $item->id,
							'name' => $item->name,
							'ingredients' => $item->materials->count_all(),
							'result' => '<img src="' . URL::base().$item->item->img(). '" />'
						)
					);
					$this->response->body(json_encode($data));
				}
				else {
					return $this->response->body(json_encode(array('action' => 'error', 'errors' => array(array('field' => 'ingredients', 'msg' => array($mat_fail))))));
				}
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
