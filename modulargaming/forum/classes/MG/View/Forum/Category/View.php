<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Category_View extends Abstract_View_Forum_Category {

	/**
	 * @var Model_Forum_Topic[]
	 */
	public $topics;

	public function title()
	{
		return 'Forum - '.$this->category->title;
	}

	public function category()
	{
		return $this->category->as_array();
	}

	public function topics()
	{
		$topics = array();

		foreach ($this->topics as $topic)
		{
			$last_post = $topic->last_post;

			$topics[] = array(
				'title'   => $topic->title,
				'replies' => $topic->replies,
				'views'   => $topic->views,
				'locked'  => $topic->locked,
				'sticky'  => $topic->sticky,
				'poll'    => $topic->poll->loaded(),
				'created' => Date::format($topic->created),
				'href'    => Route::url('forum.topic', array('id' => $topic->id)),
				'user'    => array(
					'username' => $topic->user->username,
					'href'     => Route::url('user.profile', array('id' => $topic->user->id))
				),
				'last_post' => array(
					'created' => Date::format($last_post->created),
					'user'    => array(
						'username' => $last_post->user->username,
						'href'     => Route::url('user.profile', array('id' => $last_post->user->id))
					),
				)
			);
		}

		return $topics;
	}

	public function links()
	{
		return array(
			'create' => Route::url('forum.category', array(
				'action' => 'create',
				'id'     => $this->category->id
			)),
		);
	}

	public function can_create()
	{
		return $this->_user_can('Forum_Topic_Create', array('category' => $this->category));
	}

}
