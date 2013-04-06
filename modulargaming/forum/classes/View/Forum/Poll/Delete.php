<?php defined('SYSPATH') OR die('No direct script access.');
 
class View_Forum_Poll_Delete extends Abstract_View_Forum_Poll {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Delete',
				'href'  => Route::url('forum.poll', array(
					'id'     => $this->topic->id,
					'action' => 'delete'
				))
			)
		));
	}

}
