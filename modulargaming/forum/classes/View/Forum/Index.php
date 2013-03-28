<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Index extends Abstract_View_Forum {

	public $title = 'Forum';

	/**
	 * @var Model_Forum_Category[]
	 */
	public $categories;

	public function categories()
	{
		$categories = array();
		foreach ($this->categories as $category)
		{
			$categories[] = array(
				'title'       => $category->title,
				'description' => $category->description,
				'topics'      => number_format(ORM::factory('Forum_Topic')->where('category_id', '=', $category->id)->count_all()),
				'href'        => Route::url('forum.category', array('id' => $category->id)),
			);
		}
		return $categories;
	}

}
