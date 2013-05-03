<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Post_Delete extends Abstract_View_Forum_Post {

	public function title()
	{
		return 'Deleting post #'.$this->post->id;
	}

	public function post()
	{
		$post = $this->post;
		$user = $post->user;

		return array(
			'id'      => $post->id,
			'content' => $post->content,
			'created' => Date::format($post->created),
			'user'    => array(
				'avatar'     => $user->avatar(),
				'username'   => $user->username,
				'signature'  => $user->get_property('signature'),
				'post_count' => number_format($user->get_property('forum.posts')),
				'created'    => Date::format($user->created),
				'href'       => Route::url('user.profile', array('id' => $user->id))
			)
		);
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Delete #'.$this->post->id,
				'href' => Route::url('forum.post', array(
					'action' => 'delete',
					'id'     => $this->post->id,
				))
			)
		));
	}


}
