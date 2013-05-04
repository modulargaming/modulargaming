<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Message_View extends Abstract_View_Message {

	public $message;

	public function title()
	{
		return $this->message->subject;
	}

	public function message()
	{
		$message[] = array(
			'id' => $this->message->id,
			'subject' => $this->message->subject,
			'content' => $this->message->content, // Escaped properly at create now
			'created' =>  Date::format($this->message->created),
			'sender' => array(
				'avatar' => $this->message->sender->avatar(),
				'username'  => $this->message->sender->username,
				'title'  => $this->message->sender->title->title,
				'signature' => $this->message->sender->get_property('signature'),
				'created' => Date::format($this->message->created),
				'href'      => Route::url('user.profile', array(
					'id'     => $this->message->sender->id,
				)),
			),

		);
		return $message;
	}

	public function links()
	{
		return array(
			'reply' => Route::url('message.create', array(
				'action' => 'index',
				'id'     => $this->message->sender->id
			)),
		);
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $this->message->subject,
				'href'  => Route::url('message.view', array('id' => $this->message->id))
			)
		));
	}

}
