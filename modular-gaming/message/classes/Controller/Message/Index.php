<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Message_Index extends Controller_Message {

	public function action_index()
	{
		$this->view = new View_Message_Index;

		// TODO: Add pagination
		$messages = ORM::factory('Message')
			->where('receiver_id', '=', $this->user->id)
			->order_by('created', 'DESC')
			->find_all();

		$this->view->messages = $messages;
	}


}
