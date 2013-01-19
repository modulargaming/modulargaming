<?php defined('SYSPATH') OR die('No direct script access.');

class View_Message_View extends Abstract_View {

        public $message;

        public function title()
        {
                return $this->message->subject;
        }

        public function message()
        {
		$message[] = array(
				'id' => $this->message->id,
				'title' => $this->message->subject,
				'content' => $this->message->content, // Escaped properly at create now
				'created' =>  Date::format($this->message->created),
				'sender' => array(
					'avatar' => $this->message->sender->avatar,
					'username'  => $this->message->sender->username,
					'title'  => $this->message->sender->title->title,
					'signature' => $this->message->sender->signature,
					'created' => Date::format($this->message->created),
					'href'      => Route::url('user.view', array(
						'id'     => $this->message->sender->id,
					)),
				),

				);
                return $message;
        }


}

