<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Adopt extends Abstract_View_Pet {

	public $title = 'Adopt a pet';

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Adopt a pets',
				'href'  => Route::url('pet.adopt')
			)
		));
	}

}
