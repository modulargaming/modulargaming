<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Item_Type extends ORM {

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
			'action' => array(
				array('not_empty'),
				array('max_length', array(':value', 200)),
			),
			'default_command' => array(
				array('not_empty'),
				array('max_length', array(':value', 100)),
			),
			'img_dir' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
		);
	}
	
	public function filters()
	{
		return array(
			'img_dir' => array(
				array(array($this, 'dir_check'))
			)
		);
	}
	
	public function dir_check($value){
		return (substr($value, -1) != '/') ? $value.'/' : $value;
	}

} // End Item Type Model
