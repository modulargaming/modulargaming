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

	public static function topic_exists($id)
	{
		$topic = ORM::factory('Forum_Topic', $id);

		return $topic->loaded();
	}

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

	public function create_topic($values, $expected)
	{
		// Validation for category
		$extra_validation = Validation::Factory($values)
			->rule('category_id', 'Model_Forum_Category::category_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}

	public function delete()
	{
		$this->delete_posts();
		parent::delete();
	}

	/**
	 * Delete all forum posts, and recalculate the users post count.
	 *
	 * Loops the posts to locate all users and calls delete on them.
	 */
	public function delete_posts()
	{
		$users = array();

		foreach ($this->posts->find_all() as $post)
		{
			$users[$post->user->id] = $post->user;
			$post->delete();
		}

		foreach ($users as $user)
		{
			$user->set_property('forum.posts', Model_Forum_Post::get_user_post_count($this->user->id));
			$user->save();
		}
	}

	/**
	 * Delete all topics for the specified category.
	 * Mysql will delete all related topics AND posts using "On Delete CASCADE".
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
