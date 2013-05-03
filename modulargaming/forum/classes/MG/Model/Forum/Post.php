<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Model_Forum_Post extends ORM {


	protected $_table_columns = array(
		'id'          => NULL,
		'topic_id'       => NULL,
		'user_id'    => NULL,
		'content'    => NULL,
		'created'      => NULL,
		'updated'	=> NULL
	);

	protected $_belongs_to = array(
		'topic' => array(
			'model' => 'Forum_Topic',
			'foreign_key' => 'topic_id',
		),
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id',
		),
	);

	protected $_load_with = array(
		'user'
	);

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE
	);

	protected $_updated_column = array(
		'column' => 'updated',
		'format' => TRUE,
	);

	/**
	 * Get the number of posts a user has.
	 *
	 * @param int $id user id
	 * @return int
	 */
	public static function get_user_post_count($id)
	{
		return ORM::factory('Forum_Post')
			->where('user_id', '=', $id)
			->count_all();
	}

	public function rules()
	{
		return array(
			'content' => array(
				array('not_empty'),
				array('max_length', array(':value', 1024)),
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

	/**
	 * Create a new post and recalculate post count.
	 *
	 * @param array $values
	 * @param array $expected
	 * @return ORM
	 */
	public function create_post($values, $expected)
	{
		// Validation for topic
		$extra_validation = Validation::Factory($values)
			->rule('topic_id', 'Model_Forum_Topic::topic_exists');

		$this->values($values, $expected)
			->create($extra_validation);

		// Update the post count for the post owner.
		$user = $this->user;
		$user->set_property('forum.posts', $user->get_property('forum.posts') + 1);
		$user->save();

		return $this;
	}

	/**
	 * Recalculate the post count on delete.
	 *
	 * @return ORM
	 */
	public function delete()
	{
		// Update topic replies.
		$this->topic->replies--;
		$this->topic->save();

		// Update post count.
		$user = $this->user;
		$user->set_property('forum.posts', $user->get_property('forum.posts') - 1);
		$user->save();

		return parent::delete();
	}

}
