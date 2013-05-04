<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_Forum_Category_Index extends Abstract_View_Admin {

	public $title = 'Forum Categories';

	public function categories()
	{
		$categories = array();

		foreach ($this->categories as $category)
		{
			$categories[] = array(
				'id'          => $category->id,
				'title'        => $category->title,
				'description' => $category->description,
				'locked'      => (boolean) $category->locked
			);
		}

		return $categories;
	}

}
