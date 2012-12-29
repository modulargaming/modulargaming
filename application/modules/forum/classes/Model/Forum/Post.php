<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Post extends ORM {

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE
	);

	protected $_belongs_to = array(
		'topic' => array(
			'model' => 'Forum_Topic',
		),
	);

	protected $_has_one = array(
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id',
		),
	);


	public function rules()
	{
		return array(
			'content' => array(
				array('not_empty'),
				array('max_length', array(':value', 1024)),
			),
		);
	}

	public function create_post($values, $expected)
	{
		// Validation for topic
		$extra_validation = Validation::Factory($values)
			->rule('topic_id', 'Model_Forum_Topic::topic_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}

	static public function post_exists($id)
	{
		$post = ORM::factory('Forum_Post', $id);

		return $post->loaded();
	}

}

