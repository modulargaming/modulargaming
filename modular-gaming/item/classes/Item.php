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
	
	static public function parse_commands($input) {
		$commands = array();
			
		foreach($input as $k => $c) {
			//if we're dealing string as parameter or an assoc array
			if(!is_array($c) OR count(array_filter(array_keys($c), 'is_string')) > 0) {
				$commands[] = array('name' => $k, 'param' => $c);
			}
			else {
				//if multiple command instances were defined (non-assoc array)
				foreach($c as $p) {
					$commands[] = array('name' => $k, 'param' => $p);
				}
			}
		}
		return $commands;
	}
	
	static public function list_commands() {
		static $commands = null;
		
		if($commands == null)
		{
			// Include paths must be searched in reverse
			$paths = array_reverse(Kohana::include_paths());
			
			$base = 'classes/Item/Command/';
			
			// Array of class names that have been found
			$found = array();
			
			foreach ($paths as $dir)
			{
				if (is_dir($dir.$base))
				{
					$found = array_merge($found, self::_read_command_dir($dir.$base, true, $dir.$base));
				}
			}
			
			$commands = $found;
		}
		
		return $commands;
	}
	
	static protected function _read_command_dir($dir, $top_level = false, $replace) {
		$found = array();
		$handle = opendir($dir);
			
		while (false !== ($entry = readdir($handle))) {
			if($entry == '.' || $entry == '..')
				continue;
			else if(is_dir($dir.$entry))
				$found = array_merge($found, self::_read_command_dir($dir.$entry.'/', false, $replace));
			else if(is_file($dir.$entry) && $top_level == false) {
				$found[] = array('name' => str_replace('/', '_', str_replace('.php', '', str_replace($replace, '', $dir).$entry)));
			}
		}
		
		closedir($handle);
		
		return $found;
	}
	
	static public function filter_type_dir($value){
		return (substr($value, -1) != '/') ? $value.'/' : $value;
	}
	
	static public function validate_commands($validation, $value) {
		$values = json_decode($value, true);
		
		foreach($values as $command) {
			$cmd = Item_Command::factory($command['name']);
			
			if(!$cmd->validate($command['param']))
			{
				$validation->error('commands', $command['name']);
			}
		}
	}
}