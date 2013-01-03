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
	//			'content' => Security::xss_clean($post->content), // TODO: Is this escaped properly at save?
				'content' => $post->content, // Escaped properly at create now
				'created' =>  Date::format($post->created),
				'user' => array(
					'avatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower($post->user->email)) . '?s=64',
					'username'  => $post->user->username,
					'signature' => $post->user->signature,
					'post_count' => number_format($post->user->post_count),
					'href'      => Route::url('user', array(
						'action' => 'view',
						'id'     => $post->user->id,
					)),
				),
				'links' => array(
					'edit' => Route::url('forum/post', array(
						'action' => 'edit',
						'id'     => $post->id,
					)),
					'delete' => Route::url('forum/post', array(
						'action' => 'delete',
						'id'     => $post->id,
					)),
				),
				'can_edit' => Auth::instance()->get_user()->can('Forum_Post_Edit', array('post' => $post)),
				'can_delete' => Auth::instance()->get_user()->can('Forum_Post_Delete', array('post' => $post)),
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

	public function actions()
	{
		$actions = array();
		$user = Auth::instance()->get_user();

		if ($user->can('Forum_Topic_Delete'))
		{
			$actions[] = array(
				'title' => 'Delete',
				'href'  => Route::url('forum/topic', array(
					'action' => 'delete',
					'id'     => $this->topic->id,
				)),
			);
		}

		return $actions;
	}

	public function has_actions()
	{
		$actions = $this->actions();
		return ! empty($actions);
	}

}

