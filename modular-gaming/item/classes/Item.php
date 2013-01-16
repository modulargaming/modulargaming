<?php

class Item {
	
	protected $_item = null;
	protected $_type = '';
	
	public function __construct($item) {
		if(Valid::digit($item)) 
		{
			$id = $item;
			$item = ORM::factory('Item', $id);
			if($item->loaded())
			{
				$this->_item = $item;
				$this->_type = 'item';
			}
			else
				throw new Item_Exception('Item ":id" could not be loaded', array(':id' => $id));
		}
		else if($item->loaded())
		{
			$this->_item = $item;
			if(is_a($item, 'Model_Item'))
				$this->_type = 'item';
			else if(is_a($item, 'Model_User_Item'))
				$this->_type = 'user_item';
			else
			{
				throw new Item_Exception('The supplied item\'s resource does not come from a model.');
				$this->_item = null;
			}
		}
		else
			throw new Item_Exception('Item ":name" could not be loaded', array(':name' => $item->name));
	}
	
	public function to_user($user, $amount=1, $location='inventory') {
		if(!Valid::digit($amount))
			throw new Item_Exception('The supplied amount should be a number.');
		
		if(Valid::digit($user))
			$user = ORM::factory('User', $id);
		else if(!is_a($user, 'Model_User'))
			throw new Item_Exception('The supplied user does not come from a model.');
		
		if(!$user->loaded())
			throw new Item_Exception('The supplied user does not exist.');
		else 
		{
			if($this->_type == 'item') {
				$user_item = ORM::factory('User_item')
					->where('user_id', '=', $user->id)
					->where('item_id', '=', $this->_item->id)
					->where('location', '=', $location)
					->find();
			}
			else 
				$user_item = $this->_item;
			
			$action = ($amount > 0) ? '+' : '-';
			if($user_item->loaded())
			{
				//update item amount
				$user_item->amount($action, $amount);
			}
			else if($action == '+')
			{
				$id = ($this->_type == 'item') ? $this->_item->id : $this->_item->item_id;
				
				//create new copy
				$user_item = ORM::factory('User_Item')
					->values(array('user_id' => $user->id, 'item_id' => $id, 'location' => $location, 'amount' => $amount))
					->save();
			}
		}
	}
	
	public function from_user($user, $amount=1, $location='inventory') {
		$this->to_user($user, '-'.$amount);
	}
	
	static public function factory($item) {
		return new Item($item);
	}
	
	static public function filter_type_dir($value){
		return (substr($value, -1) != '/') ? $value.'/' : $value;
	}
	
	//@todo validate item commands
}