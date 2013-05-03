<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Model_Forum_Poll_Option extends ORM {


	protected $_table_columns = array(
		'id'          => NULL,
		'poll_id'       => NULL,
		'title'    => NULL,
		'votes'    => NULL
	);

	protected $_belongs_to = array(
		'poll' => array(
			'model' => 'Forum_Poll',
			'foreign_key' => 'poll_id',
		),
	);

	public static function option_exists($id)
	{
		$option = ORM::factory('Forum_Poll_Option', $id);
		return $option->loaded();
	}

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
		);
	}

	public function create_option($values, $expected)
	{
		// Validation for option
		$extra_validation = Validation::Factory($values)
			->rule('poll_id', 'Model_Forum_Poll::poll_exists');
 		return $this->values($values, $expected)
			->create($extra_validation);
	}

}
