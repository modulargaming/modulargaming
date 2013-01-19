<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Message_View extends Controller_Message {


        /**
         * View users profile
         */
        public function action_index()
        {
                $id = $this->request->param('id');

                $message = ORM::factory('Message', $id);

                if ( ! $message->loaded())
                {
                        throw HTTP_Exception::Factory('404', 'No such message');
                }

		if ($message->receiver_id != $this->user->id)
		{
			throw HTTP_Exception::Factory('403', 'Message does not belong to you');
		}

                $this->view = new View_Message_View;
                $this->view->message = $message;
        }


}
