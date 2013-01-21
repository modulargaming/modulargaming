<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Inventory extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	
	public function action_index()
	{
		$this->view = new View_Inventory_Index;
		
		//$max_items = Kohana::$config->load('items.inventory.pagination');
		
		$items = ORM::factory('User_Item')
			->where('user_id', '=', $this->user->id)
			->where('location', '=', 'inventory')
			->find_all();
		
		$this->view->items = $items;
	}
	
	public function action_view() {
		$item = ORM::factory('User_Item')
			->where('id', '=', $this->request->param('id'))
			->find();
		
		$errors = array();
		
		if($item->user_id != $this->user->id)
			$errors[] = 'You can\'t access another player\'s item';
		if($item->location != 'inventory')
			$errors[] = 'The item you want to view is not located in your inventory.';
		
		if(count($errors) == 0) {
			$this->view = new View_Inventory_View;
			$this->view->item = $item;
			
			//generate action list
			$actions = array();
			$default_command = Item_Command::factory($item->item->type->default_command);
			
			if($default_command->pets_required()) {
				$pets = ORM::factory('User_Pet')
					->where('user_id', '=', $this->user->id)
					->find_all();
				
				if(count($pets) > 0) {
					foreach($pets as $pet) {
						$actions[$pet->id] = __($item->item->type->action, array(':pet_name' => $pet->name));
					}
				}
			}
			else
				$actions['consume'] = $item->item->type->action;
			
			$actions['move_safe'] = 'Move to safe';
			
			if($item->item->transferable == true)
				$actions['gift'] = 'Send as gift';
			
			$actions['remove'] = 'Remove item';
			$this->view->action_list = $actions;
		}
		else 
		{
			//return error
		}
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
			$def_cmd = Item_Command::factory($item->type->default_command);
			
			if(Valid::digit($action)) {
				//we'll want to perform an action on a pet
				$pet = ORM::factory('User_Pet', $action);
				
				if(!$pet->loaded())
					$errors[] = 'No existing pet has been specified';
				if($pet->user_id != $this->user->id)
					$errors[] = 'You can\'t let a pet comsume this item if it\'s not yours';				
				if($def_cmd->pets_required == false)
					$errors[] = 'can\'t perform this item action on a pet';
				
				if(count($errors) == 0) {
					$commands = $item->commands;
					$results = array();
					
					$db = Database::instance();
					$db->begin();
					
					//rework to use events to events can be stopped on errors
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
						$db->commit();
						
						Hint::success($results[0]);
						$this->redirect(Route::get('item.inventory'));
					}
				}
			}
			else {
				$results = array();
				
				switch($action) {
					default :
						if(substr($action, 0, 5) == 'move_') { //Moving items can take an amount
							$location = substr($action, 4);
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
								$res = $cmd->perform($item, $amount);
								
								if($res == TRUE) {
									$name = ($amount > 1) ? Inflector::plural($item->item->name, $amount) : $item->item->name;
									
									$results = __(':amount :items have been moved to your :location', array(
										':amount' => $amount, ':items' => $name, ':location' => $location		
									));
								}
							}
							
						}
						else //fallback to any unexisting item actions
							$errors[] = 'The action you want to perform with this item does not exist';
						break;
					case 'remove' : //takes an amount
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
							if ($amount > 1) {
								$name = Inflector::plural($item->name, $amount);
								$verb = 'were';
							}
							else {
								$name = $item->name;
								$verb = 'was';
							}
							
							$item->amount('-', $amount);
							$results = $amount.' '.$name.' '.$verb.' deleted';
						}
						break;
					case 'gift' :
						
						break;
				}
			}
		}
		
		if(count($errors) > 0) {
			foreach($errors as $er)
				Hint::ERROR($er);
		}
		else {
			Hint::success($results);
		}
		
		$this->redirect(Route::get('item.inventory'));
	}
}
