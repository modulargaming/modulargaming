<?php

class Item_Command_General_Remove extends Item_Command {
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
}