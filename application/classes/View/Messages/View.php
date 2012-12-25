<?php defined('SYSPATH') OR die('No direct script access.');

class View_Messages_View extends View_Base {

        public $message;

        public function title()
        {
                return $this->message['subject'];
        }

        public function message()
        {
                $message = $this->message;
                $message['time'] = Date::format($message['time']);

                return $message;
        }


}

