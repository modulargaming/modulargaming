<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Topic extends Controller_Frontend {

	public function action_view()
	{
		$id = $this->request->param('id');

		$topic = ORM::factory('Forum_Topic', $id);

		if ( ! $topic->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum topic not found');
		}

		$posts = $topic->posts->find_all();

		$this->view = new View_Forum_Topic;
		$this->view->topic = $topic;
		$this->view->posts = $posts;
}

}

