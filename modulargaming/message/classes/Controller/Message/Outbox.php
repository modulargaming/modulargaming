<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Message_Outbox extends Abstract_Controller_Message {

	public function action_index()
	{
		$this->view = new View_Message_Index;

		// TODO: Add pagination
		$messages = ORM::factory('Message')
			->where('receiver_id', '=', $this->user->id)
			->where('sent', '=', 1)
			->order_by('created', 'DESC');

		$paginate = Paginate::factory($messages)
			->execute();

		$this->view->pagination = $paginate->render();
		$this->view->messages = $paginate->result();
		$this->view->outbox = 1;
	}


}
