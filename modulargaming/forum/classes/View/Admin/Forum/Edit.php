<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Forum_Edit extends Abstract_View_Admin_Forum {

	/**
	 * @var Model_Forum_Category Category
	 */
	public $category;

	public $_partials = array(
		// 'modal' => 'Admin/Modal/Forum/Edit'
	);

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Edit category - '.$this->category->title,
				'href' => Route::url('forum.admin', array(
					'action' => 'edit',
					'id'     => $this->category->id
				))
			)
		));
	}

}
