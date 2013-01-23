<?php

class Item_Command_Move_Safe extends Item_Command_Move {
	public $default = true;
	
	protected function _build($name){
		return null;
	}
	
	public function validate($param) {
		return null;
	}
	
	public function perform($item, $amount, $data=null) {
		$name = $item->item->name($amount);
		
		if(!$item->move('safe', $amount))
			return false;
		else
			return 'You have successfully moved ' . $name . ' to your safe.';
	}
	
}