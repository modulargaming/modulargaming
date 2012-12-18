<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Messages extends Controller_Frontend {

	public function action_index()
	{
		$this->view = new View_Messages_Index;
		$message = ORM::factory('Message');
		$message->where('to_id', '=', $this->user->id);
   		$this->view->total = $message->count_all();

	}


	public function list_messages()
	{
		$message = ORM::factory('Message');
		$message->where('to_id', '=', $this->user->id);
		$messages = $message->find_all();

    		$list = array();

    		foreach ($messages as $message)
    		{
        		$list[] = array(
            		'href'     => Route::url('messages', array('id' => $message->id)),
            		'subject' => $message->subject,
			'text' => $message->text,
			'from_id' => $message->from_id,
			'folder_id' => $message->folder_id,
        		);
    		}

   	 	return $list;
	}

        public function action_create()
        {
                if ($_POST)
                {
                        try
                        {

				$array = $this->request->post();
				$array['from_id'] = $this->user->id;
                                $message = ORM::Factory('Message')
                                        ->create_message($array, array(
                                                'to_id',
                                                'subject',
                                                'text',
						'from_id',
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
