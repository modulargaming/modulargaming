<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Post_Delete extends Abstract_View {

	public $post;

	public function title()
	{
		return 'Deleting post #'.$this->post->id;
	}

	public function post()
	{
		$post = $this->post;
		return array(
			'id' => $post->id,
			'content' => $post->content, // Escaped properly at create now
			'created' =>  Date::format($post->created),
			'user' => array(
				'avatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower($post->user->email)) . '?s=64',
				'username'  => $post->user->username,
				'signature' => $post->user->signature,
				'post_count' => number_format($post->user->post_count),
				'created' => Date::format($post->user->created),
				'href'      => Route::url('user', array(
					'action' => 'view',
					'id'     => $post->user->id,
				)),
			)
		);
	}


}

