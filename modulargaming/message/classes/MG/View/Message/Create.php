<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Message_Create extends Abstract_View_Message {

	public $title = 'Create Message';


	public function username()
	{
		return $this->username;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Create Message',
				'href'  => Route::url('message.create', array('username' => $this->username))
			)
		));
	}

}
