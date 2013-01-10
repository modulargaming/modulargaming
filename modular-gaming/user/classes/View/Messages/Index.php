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
				'subject' => $message->subject,
				'content' => $message->content,
				'sender' => $message->sender,
				'id'	=> $message->id,
			);
		}

		return $messages;
	}

	public function total_messages()
	{
		return count($this->messages);
	}


}
