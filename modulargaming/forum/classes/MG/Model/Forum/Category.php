<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Model_Forum_Category extends ORM {

	protected $_table_columns = array(
		'id'           => NULL,
		'title'        => NULL,
		'description'  => NULL,
		'locked'       => NULL,
		'created'      => NULL,
		'topics_count' => NULL  // Number of topics in category.
	);

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE
	);

	protected $_has_many = array(
		'topics' => array(
			'model'       => 'Forum_Topic',
			'foreign_key' => 'category_id',
		),
	);

	public static function category_exists($id)
	{
		$category = ORM::factory('Forum_Category', $id);

		return $category->loaded();
	}

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
			'description' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
		);
	}

	public function create_category($values, $expected)
	{
		return $this->values($values, $expected)
			->create();
	}

	public function is_locked()
	{
		return $this->locked === '1';
	}

	/**
	 * Delete all topics for this category, delegates the work to Forum_Topic model.
	 *
	 * @see Model_Forum_Topic::delete_all_topics_for_category
	 */
	public function delete_all_topics()
	{
		return Model_Forum_Topic::delete_all_topics_for_category($this->id);
	}

	/**
	 * Move all topics for this category to the new category, delegates the work to Forum_Topic model.
	 *
	 * @see Model_Forum_Topic::move_all_topics_for_category
	 * @param  int $new_id category_id to move to.
	 * @return mixed
	 */
	public function move_all_topics_to($new_id)
	{
		return Model_Forum_Topic::move_all_topics_for_category($this->id, $new_id);
	}

}
