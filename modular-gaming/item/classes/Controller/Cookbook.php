<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Cookbook extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	
	public function before() {
		Breadcrumb::add('Cook book', Route::url('item.cookbook'));
		parent::before();
	}
	
	public function action_index()
	{
		$this->view = new View_Item_Cookbook_Index;
		
		$config = Kohana::$config->load('items.cookbook');
		
		if($config['ajax'] === true) 
		{
			Assets::js('item.cookbook', 'item/cookbook.js');
		}
		
		$items = ORM::factory('User_Item')
			->where('user_id', '=', $this->user->id)
			->where('location', '=', 'cookbook')
			->find_all();
		
		$this->view->recipes = $items;
		$this->view->links = array(
				array('name' => 'Inventory', 'link' => Route::get('item.inventory')->uri()),
				array('name' => 'Safe', 'link' => "#"),
				array('name' => 'Shop', 'link' => "#"),
		);
	}
	
	public function action_view() {
		$id = $this->request->param('id');		
		$item = ORM::factory('User_Item', $id);
		
		Breadcrumb::add('Cook '.$item->item->name, URL::site(Route::get('item.cookbook.view')->uri(array('id' => $id))));
		
		$errors = array();
		
		if(!$item->loaded()) 
		{
			$errors[] = 'Recipe could not be found';
		}
		else if($item->user_id != $this->user->id) 
		{
			$errors[] = 'You can\'t access another player\'s recipe';
		}
		else if($item->location != 'cookbook') 
		{
			$errors[] = 'The recipe you want to view is not located in your cookbook.';
		}
		else if($item->item->type->default_command != 'General_Cook') 
		{
			$errors[] = 'The item you want to use as a recipe just isn\'t cut out for it.';
		}
		else
		{		
			$recipe = ORM::factory('Item_Recipe')
				->where('item_recipe.name', '=', $item->item->commands[0]['param'])	
				->find();
			$coll = $recipe->materials->find_all();
			
			$materials = array();
			
			$collect_count = 0;
			
			foreach($coll as $material) {
				$user_item = Item::factory($material->item)
					->user_has('inventory');
				
				$mat = 0;
				
				if ($user_item != FALSE) {
					if ($user_item->amount >= $material->amount) 
					{
						 $mat = $material->amount;
						 $collect_count++;
					}
					else 
					{
						$mat = $user_item->amount;
					}	
				}
				
				$materials[] = array(
					'name' => $material->item->name,
					'img' => $material->item->img(),
					'amount_needed' => $material->amount,
					'amount_owned' => $mat	
				);
			}
			
			$collect_count = ($collect_count == count($coll));
			
			if ($this->request->is_ajax())
			{
				$this->response->headers('Content-Type', 'application/json');
				
				return $this->response->body(json_encode(array(
					'status' => 'success', 
					'material' => $materials,
					'name' => $recipe->name,
					'id' => $item->id,
					'img' => $recipe->item->img(),
					'collected' => $collect_count
				)));
			}
			
			$this->view = new View_Item_Cookbook_View;
			$this->view->id = $item->id;
			$this->view->recipe = $recipe;
			$this->view->materials = $materials;
			$this->view->collected = $collect_count;
		}
		
		if(count($errors) > 0)
		{
			Hint::error($errors[0]);
			
			$this->redirect(Route::get('item.cookbook')->uri());
		}		
	}

	public function action_complete() {
		$item = ORM::factory('User_Item', $this->request->param('id'));
		$action = $this->request->post('action');
		
		$errors = array();
		
		if(!$item->loaded())
		{
			$errors[] = 'You can\'t use a recipe that does not exist';
		}
		else if($item->user_id != $this->user->id)
		{
			$errors[] = 'You can\'t access another player\'s recipe';
		}
		else if($item->location != 'cookbook')
		{
			$errors[] = 'The recipe you want to view is not located in your cookbook';
		}
		else if($item->item->type->default_command != 'General_Cook')
		{
			$errors[] = 'You can\'t use this item as a recipe.';
		}
		else if($action == null) {
			$errors[] = 'No action to perform has been specified';
		}
		else
		{
			$recipe = ORM::factory('Item_Recipe')
			->where('item_recipe.name', '=', $item->item->commands[0]['param'])
			->find();
			
			$coll = $recipe->materials->find_all();
			$materials = 0;
			$db = Database::instance();
			$db->begin();
			
			foreach($coll as $material) {
				$user_item = Item::factory($material->item)
					->user_has('inventory');
				
				if($user_item != FALSE && $user_item->amount >= $material->amount)
				{
					$user_item->amount('-', $material->amount);
					$materials++;
				}
			}
			
			if($materials == count($coll)) 
			{
				Item::factory($recipe->item)->to_user($this->user);
				$item->amount('-', 1);
				$db->commit();
				$result = 'You\'ve successfully made ' . $recipe->item->name;
			}
			else 
			{
				$db->rollback();
				$errors[] = 'You don\'t have all the required ingredients for this recipe.';
			}
		}
		
		if(count($errors) > 0)
		{
			Hint::error($errors[0]);
		}
		else
		{
			Hint::success($result);
		}
		
		$this->redirect(Route::get('item.cookbook')->uri());
	}
}
