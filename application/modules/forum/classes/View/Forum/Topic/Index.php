<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Index extends View_Base {

	public $topic;
	public $posts;

	public function title()
	{
		return 'Forum - ' . $this->topic->title;
	}

	public function topic()
	{
		return $this->topic->as_array();
	}

	public function posts()
	{
		$posts = array();

		foreach ($this->posts as $post)
		{
			$posts[] = array(
				'content' => $post->content,
				'href' => Route::url('forum/post', array('id' => $post->id)),
				'created' =>  Date::format($post->created),
				'user' => $post->user->as_array()
			);
		}

		return $posts;
	}

	public function href()
	{
		return array(
			'reply' => Route::url('forum/topic', array(
				'action' => 'reply',
				'id'     => $this->topic->id
			)),
		);
	}

}

