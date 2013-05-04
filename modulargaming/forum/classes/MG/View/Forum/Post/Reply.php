<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Post_Reply extends Abstract_View_Forum_Topic {

	public $title = 'Reply';

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Reply',
				'href'  => Route::url('forum.topic', array(
					'action' => 'reply',
					'id'     => $this->topic->id
				))
			)
		));
	}

}
