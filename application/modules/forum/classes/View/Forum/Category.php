<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Category extends View_Base {

	public $category;
	public $topics;

	public function title()
	{
		return 'Forum - ' . $this->category->title;
	}

	public function category()
	{
		return $this->category->as_array();
	}

	public function topics()
	{
		$topics = array();

		foreach ($this->topics as $topic)
		{
			$topics[] = array(
				'title' => $topic->title,
				'href' => Route::url('forum/topic', array('id' => $topic->id)),
			);
		}

		return $topics;
	}

	public function href()
	{
		return array(
			'create' => Route::url('forum/category', array(
				'action' => 'create',
				'id'     => $this->category->id
			)),
		);
	}

}
