<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Poll extends ORM {

	protected $_belongs_to = array(
		'topic' => array(
			'model' => 'Forum_Topic',
			'foreign_key' => 'topic_id',
		),
	);

	protected $_has_many = array(
		'options' => array(
			'model' => 'Forum_Poll_Option',
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

	public function create_poll($values, $expected)
	{
		// Validation for topic
		$extra_validation = Validation::Factory($values)
			->rule('topic_id', 'Model_Forum_Topic::topic_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}

}
