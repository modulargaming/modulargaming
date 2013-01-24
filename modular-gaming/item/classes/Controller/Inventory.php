<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Inventory extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	
	public function action_index()
	{
		$this->view = new View_Item_Inventory_Index;
		
		$config = Kohana::$config->load('items.inventory');
		$max_items = $config['pagination'];
		
		if($config['ajax'] === true) {
			Assets::js('item.inventory', 'item/inventory/index.js');
		}
		
		$items = ORM::factory('User_Item')
			->where('user_id', '=', $this->user->id)
			->where('location', '=', 'inventory');
		
		$paginate = Paginate::factory($items, array('total_items' => $max_items))->execute();
		
		$this->view->pagination = $paginate->kostache();
		$this->view->items = $paginate->result();
		$this->view->links = array(
			array('name' => 'Safe', 'link' => "#"),
			array('name' => 'Shop', 'link' => "#"),
			array('name' => 'Cookbook', 'link' => Route::get('item.cookbook')->uri())
		);
	}
	
	public function action_view() {
		$id = $this->request->param('id');
		
		$item = ORM::factory('User_Item', $id);
		
		$errors = array();
		
		if(!$item->loaded())
			$errors[] = 'Item could not be found';
		else if($item->user_id != $this->user->id)
			$errors[] = 'You can\'t access another player\'s item';
		else if($item->location != 'inventory')
			$errors[] = 'The item you want to view is not located in your inventory.';
		
		if(count($errors) == 0) {			
			//generate action list
			$actions = array();
			$extra_action_fields = array();
			
			$default_command = Item_Command::factory($item->item->type->default_command);
			
			if($default_command->pets_required() == true) {
				$pets = ORM::factory('User_Pet')
					->where('user_id', '=', $this->user->id)
					->find_all();
				
				if(count($pets) > 0) {
					foreach($pets as $pet) {
						$actions[$pet->id] = array(
							'item' => __($item->item->type->action, array(':pet_name' => $pet->name)),
							'extra' => $default_command->inventory()
						);
					}
				}
			}
			else {
				$actions['consume'] = array(
					'item' => $item->item->type->action,
					'extra' => $default_command->inventory()
				);
			}
			
			$actions['move_safe'] = array(
					'item' => 'Move to safe',
					'extra' => Item_Command::factory('Move_Safe')->inventory()
				);
			
			if($item->item->transferable == true) {
				$actions['gift'] = array(
					'item' =>  'Send as gift',
					'extra' => Item_Command::factory('General_Gift')->inventory()
				);
			}
			
			$actions['remove'] = array(
					'item' => 'Remove item',
					'extra' => Item_Command::factory('General_Remove')->inventory()
			);
			
			if ($this->request->is_ajax())
			{
				$this->response->headers('Content-Type', 'application/json');
				return $this->response->body(json_encode(array('status' => 'success', 'actions' => $actions, 'name' => $item->item->name)));
			}
			
		}
		else if ($this->request->is_ajax())
		{
			$this->response->headers('Content-Type', 'application/json');
			return $this->response->body(json_encode(array('status' => 'error', 'errors' => $errors)));
		}
		else 
		{
			foreach($errors as $er)
				Hint::error($er);

			$this->redirect(Route::get('item.inventory')->uri());
		}
		
		$this->view = new View_Item_Inventory_View;
		$this->view->item = $item;
		$this->view->action_list = $actions;
		Assets::js('item.inventory', 'item/inventory/view.js');
	}

	public function action_consume() {
		$item = ORM::factory('User_Item', $this->request->param('id'));
		$action = $this->request->post('action');
		
		$errors = array();
		
		if(!$item->loaded())
			$errors[] = 'You can\'t use an item that does not exist';
		else {
			if($item->user_id != $this->user->id)
				$errors[] = 'You can\'t access another player\'s item';
			if($item->location != 'inventory')
				$errors[] = 'The item you want to view is not located in your inventory';
		}
		if($action == null)
			$errors[] = 'No action to perform has been specified';
		
		if(count($errors) == 0) {
			$def_cmd = Item_Command::factory($item->item->type->default_command);
			
			if(Valid::digit($action)) {
				//we'll want to perform an action on a pet
				$pet = ORM::factory('User_Pet', $action);
				
				if(!$pet->loaded())
					$errors[] = 'No existing pet has been specified';
				if($pet->user_id != $this->user->id)
					$errors[] = 'You can\'t let a pet comsume this item if it\'s not yours';				
				if($def_cmd->pets_required() == false)
					$errors[] = 'can\'t perform this item action on a pet';
				
				if(count($errors) == 0) {
					$commands = $item->item->commands;
					$results = array();
					
					$db = Database::instance();
					$db->begin();
					
					foreach($commands as $command) {
						$cmd = Item_Command::factory($command['name']);
						$res = $cmd->perform($item, $command['param'], $pet);
						
						if($res == false) {
							//the command couldn't be performed, spit out error, rollback changes and break the loop
							$errors[] = __(':item_name could not be used on :pet_name', array(':item_name' => $item->name, ':pet_name' => $pet->name));
							$db->rollback();
							break;
						}
						else
							$results[] = $res;
					}
					
					if(count($errors) == 0) {
						if($def_cmd->delete_after_consume == true)
							$item->amount('-', 1);
						
						$db->commit();
						
						//@todo log
						$show = Kohana::$config->load('items.inventory.consume_show_results');
						
						if($show == 'first')
							Hint::success($results[0]);
						else {
							foreach($results as $result)
								Hint::success($result);
						}
						$this->redirect(Route::get('item.inventory')->uri());
					}
				}
			}
			else {
				$results = array();
				
				switch($action) {
					case 'consume' : 
						$commands = $item->item->commands;
						$results = array();
							
						$db = Database::instance();
						$db->begin();
						
						foreach($commands as $command) {
							$cmd = Item_Command::factory($command['name']);
							$res = $cmd->perform($item, $command['param']);
						
							if($res == false) {
								//the command couldn't be performed, spit out error, rollback changes and break the loop
								$errors[] = __(':item_name could not be used', array(':item_name' => $item->name));
								$db->rollback();
								break;
							}
							else
								$results[] = $res;
						}
							
						if(count($errors) == 0) 
						{
							if($def_cmd->delete_after_consume == true)
								$item->amount('-', 1);
							
							$db->commit();
						}
						
						break;
					case 'remove' : //takes an amount
						$amount = $this->request->post('amount');
							
						if($amount == null)
							$amount = 1;
							
						if(!Valid::digit($amount)) 
						{
							$errors[] = 'The amount you submitted isn\'t a number.';
						}
						else if($amount <= 0 OR $amount > $item->amount) 
						{
							$errors[] = 'You only have '.$item->name().', not '.$amount;
						}
						else {
							if ($amount > 1) 
							{
								$name = Inflector::plural($item->name(), $amount);
								$verb = 'were';
							}
							else 
							{
								$name = $item->name();
								$verb = 'was';
							}
							
							$item->amount('-', $amount);
							$results = __(':item :verb deleted successfully', array(
								':verb' => $verb, ':item' => $name		
							));
						}
						break;
					case 'gift' : //takes a username
						$username = $this->request->post('username');
						if($this->user->username == $username)
							$errors[] = 'You can\'t send a gift to yourself';
						else 
						{
							$user = ORM::factory('User')
								->where('username', '=', $username)
								->find();
							
							if($user->loaded()) {
								$item->transfer($user);
								//@todo notification
								
								$results = __('You\'ve successfully sent :item to :username', array(
									':item' => $item->item->name, ':username' => $user->username
								));
							}
							else
								$errors[] = __('Couldn\'t find a user named ":username"', array(':username' => $username));
						}
						break;
					default :
						if(substr($action, 0, 5) == 'move_') { //Moving items can take an amount
							$location = substr($action, 5);
							$cmd = Item_Command::factory('Move_'.ucfirst($location));
									
							$amount = $this->request->post('amount');
									
							if($amount == null)
								$amount = 1;
									
							if(!Valid::digit($amount)) {
								$errors[] = 'The amount you submitted isn\'t a number.';
							}
							else if($amount <= 0 OR $amount > $item->amount) {
								$errors[] = 'You only have '.$item->name().', not '.$amount;
							}
							else {
								$results = $cmd->perform($item, $amount);
							}
									
						}
						else //fallback to any unexisting item actions
							$errors[] = 'The action you want to perform with this item does not exist';
						break;
				}
			}
		}
		
		if ($this->request->is_ajax()) {
			$return = array();
			
			if(count($errors) > 0) {
				$return = array('status' => 'error', 'errors' => $errors);
			}
			else {
				$show = Kohana::$config->load('items.inventory.consume_show_results');
				$output = array();
				
				if(!is_array($results))
					$output = $results;
				else if($show == 'first')
					$output = $results[0];
				else {
					foreach($results as $result)
						$output[] = $result;
				}
				
				$amount = ($item->loaded()) ? $item->name() : 0;
				
				$return = array('status' => 'success', 'result' => $output, 'new_amount' => $amount);
			}
			
			$this->response->headers('Content-Type', 'application/json');
			return $this->response->body(json_encode($return));
		}
		else if(count($errors) > 0) {
			foreach($errors as $er)
				Hint::error($er);
		}
		else {
			if(!is_array($results))
				Hint::success($results);
			else {
				foreach($results as $re)
					Hint::success($re);
			}
		}
		
		$this->redirect(Route::get('item.inventory')->uri());
	}
}
