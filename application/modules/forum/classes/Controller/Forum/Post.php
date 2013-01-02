<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Post extends Controller_Frontend {

	protected $protected = TRUE;

	public function action_edit()
	{
		$id = $this->request->param('id');

		$post = ORM::factory('Forum_Post', $id);

		if ( ! $post->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum post not found');
		}

		if ( ! $this->user->can('forum_post_edit', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to edit post');
		}

		$this->view = new View_Forum_Post_Edit;
		$this->view->post = $post;
	}

}

