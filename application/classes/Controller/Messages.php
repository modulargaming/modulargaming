<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Messages extends Controller_Frontend {

protected $protected = TRUE;

	public function action_index()
	{
		$this->view = new View_Messages_Index;

		// TODO: Add pagination
		$messages = ORM::factory('Message')
			->where('to_id', '=', $this->user->id)
			->find_all();

		$this->view->messages = $messages;
	}

	public function action_create()
	{
		if ($_POST)
		{
			try
			{

				$array = $this->request->post();
				$array['from_id'] = $this->user->id;
				$array['time'] = time();
				$message = ORM::Factory('Message')
					->create_message($array, array(
						'to_id',
						'subject',
						'text',
						'from_id',
						'time',
					));

				$this->redirect('messages');
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

		}

		$this->view = new View_Messages_Create;
	}


} // End Messages
