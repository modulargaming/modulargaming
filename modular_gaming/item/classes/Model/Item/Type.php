<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Item_type extends ORM {

	public function rules()
	{
		//@todo make sure img_dir has a trailing slash
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

} // End Item Type Model
