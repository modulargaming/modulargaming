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

} // End Messages
