<?php defined('SYSPATH') OR die('No direct script access.');

class View_Messages_View extends Abstract_View {

        public $message;

        public function title()
        {
                return $this->message['subject'];
        }

        public function message()
        {
                $message = $this->message;
                $message['created'] = Date::format($message['created']);

                return $message;
        }


}

