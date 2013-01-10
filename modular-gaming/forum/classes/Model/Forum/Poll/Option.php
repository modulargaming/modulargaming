<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Poll_Option extends ORM {

	protected $_belongs_to = array(
		'poll' => array(
			'model' => 'Forum_Poll',
			'foreign_key' => 'poll_id',
		),
	);

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
		);
	}

}
