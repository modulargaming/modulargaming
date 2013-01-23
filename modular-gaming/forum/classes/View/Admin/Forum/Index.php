<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Forum_Index extends Abstract_View_Admin {

	public $title = 'Categories';

	public function categories()
	{
		$categories = array();

		foreach ($this->categories as $category)
		{
			$categories[] = array(
				'id'          => $category->id,
				'title'       => $category->title,
				'description' => $category->description,
				'locked'      => $category->is_locked(),
				'created'     => Date::format($category->created),
				'edit'        => Route::url('forum.admin', array('action' => 'edit', 'id' => $category->id)),
			);
		}

		return $categories;
	}

	public function links()
	{
		return array(
			'create' => Route::url('forum.admin', array('action' => 'create'))
		);
	}

}
