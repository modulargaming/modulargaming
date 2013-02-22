<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Forum_Delete extends Abstract_View_Admin_Forum {

	/**
	 * @var Model_Forum_Category Category
	 */
	public $category;

	/**
	 * @var Database_Result Categories
	 */
	public $categories;

	public function categories()
	{
		$categories = array();
		foreach ($this->categories as $category)
		{
			$categories[] = array(
				'id'    => $category->id,
				'title' => $category->title,
			);
		}
		return $categories;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Delete category - '.$this->category->title,
				'href'  => Route::url('forum.admin', array(
					'action' => 'delete',
					'id'     => $this->category->id
				))
			)
		));
	}

}
