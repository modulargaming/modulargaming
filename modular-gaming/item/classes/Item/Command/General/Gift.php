<?php

class Item_Command_General_Gift extends Item_Command_Move {
	public $default = true;
	
	protected function _build($name){
		return null;
	}
	
	public function validate($param) {
		return null;
	}
	
	public function perform($item, $amount, $data=null) {
		return null;
	}
	
	public function inventory() {
		return array('field' => array(
				'name' => 'username',
				'type' => 'text',
				'classes' => 'input-small search',
				'button' => 'Gift'
		));
	}
}