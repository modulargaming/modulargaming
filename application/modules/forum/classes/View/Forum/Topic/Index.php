<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Index extends View_Base {

	public $topic;
	public $posts;

	public function title()
	{
		return 'Forum - '.$this->topic->title;
	}

	public function topic()
	{
		return $this->topic->as_array();
	}

	public function posts()
	{
		$posts = array();

		foreach ($this->posts as $key => $post)
		{
			$posts[] = array(
				'i' => $key + 1, // TODO: This won't work if we use pagination, do we need it? consider using id.
				'id' => $post->id,
				'content' => $post->content, // TODO: Is this escaped properly at save?
				'created' =>  Date::format($post->created),
				'user' => array(
					'username' => $post->user->username,
					'href' => Route::url('user', array(
						'action' => 'view',
						'id'     => $post->user->id,
					)),
				),
				'links' => array(
					'edit' => Route::url('forum/post', array(
						'action' => 'edit',
						'id'     => $post->id,
					)),
				),
				'can_edit' => Auth::instance()->get_user()->can('forum_post_edit', array('post' => $post)),
			);
		}

		return $posts;
	}

	public function links()
	{
		return array(
			'reply' => Route::url('forum/topic', array(
				'action' => 'reply',
				'id'     => $this->topic->id
			)),
		);
	}

}

