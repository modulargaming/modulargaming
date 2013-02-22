<?php defined('SYSPATH') OR die('No direct script access.');

class View_Message_Create extends Abstract_View_Message {

	public $title = 'Create Message';


	public function id()
	{
		return $this->id;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Create Message',
				'href'  => Route::url('message.create', array('id' => $this->id))
			)
		));
	}

}
