<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Topic extends ORM {

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE,

	);

	protected $_belongs_to = array(
		'category' => array(
			'model' => 'Forum_Category',
		),
	);

	protected $_has_many = array(
		'posts' => array(
			'model' => 'Forum_Post',
			'foreign_key' => 'topic_id',
		),
	);

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
		);
	}

	public function create_topic($values, $expected)
	{
		// Validation for category
		$extra_validation = Validation::Factory($values)
			->rule('category_id', 'Model_Forum_Category::category_exists');

 		return $this->values($values, $expected)
			->create($extra_validation);
	}

	static public function topic_exists($id)
	{
		$topic = ORM::factory('Forum_Topic', $id);

		return $topic->loaded();
	}


}
