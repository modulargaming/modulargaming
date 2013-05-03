<?php defined('SYSPATH') OR die('No direct script access.');


class MG_Controller_Message_Index extends Abstract_Controller_Message {

	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$post = $this->request->post();
				if ($post['action'] == 'read')
				{
					foreach ($post['messages'] as $message)
					{
						$message = ORM::factory('Message')
						->where('id', '=', $message)
						->where('receiver_id', '=', $this->user->id)
						->where('sent', '=', 0)
						->find();
						if ($message->loaded()) {
							$message->read = 1;
							$message->save();
						}
					}
					Hint::success('You have marked the selected messages as read');
				}
				if ($post['action'] == 'delete')
				{
					foreach ($post['messages'] as $message)
					{
						$message = ORM::factory('Message')
						->where('id', '=', $message)
						->where('receiver_id', '=', $this->user->id)
						->where('sent', '=', 0)
						->find();
						if ($message->loaded()) {
							$message->delete();
						}
					}
					Hint::success('You have deleted the selected messages');
				}
				$this->redirect(Route::get('message.inbox')->uri());
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Message_Index;

		// TODO: Add pagination
		$messages = ORM::factory('Message')
			->where('receiver_id', '=', $this->user->id)
			->where('sent', '=', 0)
			->order_by('created', 'DESC');

		$paginate = Paginate::factory($messages)
			->execute();

		$this->view->pagination = $paginate->render();
		$this->view->messages = $paginate->result();
	}


}
