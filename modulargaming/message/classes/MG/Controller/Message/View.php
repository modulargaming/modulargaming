<?php defined('SYSPATH') OR die('No direct script access.');


class MG_Controller_Message_View extends Abstract_Controller_Message {

	/**
	 * View message
	 */
	public function action_index()
	{
		$id = $this->request->param('id');

		$message = ORM::factory('Message', $id);

		if ( ! $message->loaded())
		{
			throw HTTP_Exception::Factory('404', 'No such message');
		}

		if ( ! $this->user->can('Message_View_Index', array('message' => $message)))
		{
			throw HTTP_Exception::Factory('403', 'Message does not belong to you');
		}

		if ( ! $message->read)
		{
			$message->read = 1;
			$message->save();
		}

		if ( $message->sent)
		{
			$message->sender = $message->receiver;
		}

		$this->view = new View_Message_View;
		$this->view->message = $message;
	}


}
