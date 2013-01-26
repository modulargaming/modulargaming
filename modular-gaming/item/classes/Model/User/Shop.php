<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Shop extends ORM {

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id'
		),
	);

	protected $_load_with = array('user');

	public function rules()
	{
		return array(
			'user_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'title' => array(
				array('not_empty'),
				array('min_length', array(':value', 5)),
				array('max_length', array(':value', 70)),
			),
			'description' => array(
				array('max_length', array(':value', Kohana::$config->load('items.user_shop.description_char_limit')))		
			)
		);
	}

} // End User_Shop Model
