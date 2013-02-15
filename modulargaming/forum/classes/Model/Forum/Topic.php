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
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id',
		),
		'last_post' => array(
			'model' => 'Forum_Post',
			'foreign_key' => 'last_post_id',
		),
	);

	protected $_has_one = array(
		'poll' => array(
			'model' => 'Forum_Poll',
			'foreign_key' => 'topic_id',
		),
	);

	protected $_has_many = array(
		'posts' => array(
			'model' => 'Forum_Post',
			'foreign_key' => 'topic_id',
		),
	);

	protected $_load_with = array(
		'user',
		'poll'
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

	public function filters()
	{
		return array(
			'content' => array(
				array('Security::xss_clean'),
			),
		);
	}

	public function delete()
	{
		$this->delete_posts();
		parent::delete();
	}

	public function delete_posts()
	{
		$post_users = array();
		foreach ($this->posts->find_all() as $post)
		{
			$post_users[$post->user->id] = $post->user;
			$post->delete();
		}
		foreach ($post_users as $user)
		{
			$user->calculate_post_count();
		}
	}

	public function create_topic($values, $expected)
	{
		// Validation for category
		$extra_validation = Validation::Factory($values)
			->rule('category_id', 'Model_Forum_Category::category_exists')
			->rule('category_id', 'Model_Forum_Category::category_open');
 		return $this->values($values, $expected)
			->create($extra_validation);
	}

	static public function topic_open($id)
	{
		$topic = ORM::factory('Forum_Topic', $id);

		return $topic->locked == 0;
	}

	static public function topic_exists($id)
	{
		$topic = ORM::factory('Forum_Topic', $id);

		return $topic->loaded();
	}

	/**
	 * Delete all topics for the specified category.
	 * Mysql will delete all related topics and posts using "On Delete CASCADE".
	 *
	 * @param   int  $category category_id to delete from
	 * @return  object
	 */
	public static function delete_all_topics_for_category($category)
	{
		return DB::delete('Forum_Topic')
			->where('category_id', '=', $category)
			->execute();
	}

	/**
	 * Move all topics for the specified category to the new category.
	 *
	 * @param  int $old_category category_id to move from
	 * @param  int $new_category category_id to move to
	 * @return mixed
	 */
	public static function move_all_topics_for_category($old_category, $new_category)
	{
		return DB::update('Forum_Topic')
			->where('category_id', '=', $old_category)
			->value('category_id', $new_category)
			->execute();
	}


}
