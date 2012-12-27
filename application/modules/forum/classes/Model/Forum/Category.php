<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Category extends ORM {

	protected $_has_many = array(
		'topics' => array(
			'model' => 'Forum_Topic',
			'foreign_key' => 'category_id',
		),
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
				array(array($this, 'unique'), array('name', ':value')),
			),

			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
				array(array($this, 'unique'), array('name', ':value')),
			),

			'description' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array('name', ':value')),
			),
		);
	}

	public function create_category($values, $expected)
	{
		return $this->values($values, $expected)
			->create();
	}

	static public function category_exists($id)
	{
		$category = ORM::factory('Forum_Category', $id);

		return $category->loaded();
	}


}
