<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Forum_Create extends Abstract_View_Admin_Forum {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Create category',
				'href' => Route::url('forum.admin', array('action' => 'create'))
			)
		));
	}

}
