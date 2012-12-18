<?php defined('SYSPATH') OR die('No direct script access.');

class View_Messages_Index extends View_Base
{
	public $title = 'Messages';

	public function messages()
	{
		$messages = array();

		foreach ($this->messages as $message)
		{
			$messages[] = array(
				//'href'     => Route::url('messages', array('id' => $message->id)),
				'subject' => $message->subject,
				'text' => $message->text,
				'from_id' => $message->from_id,
				'folder_id' => $message->folder_id,
			);
		}

		return $messages;
	}

	public function total_messages()
	{
		return count($this->messages);
	}

}
