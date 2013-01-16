<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Forum_Delete extends Abstract_View_Admin {

	public $category;
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

}