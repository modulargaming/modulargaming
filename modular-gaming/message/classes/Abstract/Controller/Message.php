<?php defined('SYSPATH') OR die('No direct script access.');


class Abstract_Controller_Message extends Abstract_Controller_Frontend {

	protected $protected = TRUE;


	public function before()
	{
		parent::before();

		Breadcrumb::add('Messages', Route::url('messages'));
	}
}
