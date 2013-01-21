<?php

abstract class Item_Command {
	
	static public function factory($command, $validation=null) {
		$cmd = 'Item_Command_'.$command;
		return new $cmd($validation);
	}
	
	public $default = false;
	public $load_pets = false;
	protected $_validation = null;
	
	abstract protected function _build($name);
	abstract public function validate($param);
	abstract public function perform($item, $param, $data=null);
	
	public function __construct(Kohana_Validation $validation=null) {
		$this->_validation = $validation;
	}
	
	public function build_admin($name) {
		$def = $this->_build($name);
		
		//if loading pets is required
		$def['pets'] = ($this->pets_required()) ? 1 : 0;
		
		//if multiple instances of the command may be defined
		if(!isset($def['multiple']))
			$def['multiple'] = 0;
		
		//if autocomplete search is needed
		if(!isset($def['search']))
			$def['search'] = 0;
		
		return $def;
	}
	public function is_default() {
		return $this->default;
	}
	
	public function pets_required() {
		return $this->load_pets;
	}
}