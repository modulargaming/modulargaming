<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Index extends View_Base {

	public $title = 'Forum';
	public $categories;

	public function categories()
	{
		$categories = array();
		foreach ($this->categories as $category)
		{
			$categories[] = array(
				'title'       => $category->title,
				'description' => $category->description,
				'href'        => Route::url('forum.category', array(
					'id' => $category->id
				)),
			);
		}
		return $categories;
	}

}