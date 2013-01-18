<?php

abstract class Item_Command {
	
	public $default = false;
	public $load_pets = false;
	
	abstract public function build_form($name);
	abstract public function validate($param);
	abstract public function perform($item, $data);
	
	public function is_default() {
		return $this->default;
	}
	
	public function pets_required() {
		return $load_pets;
	}
}