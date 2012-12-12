<?php defined('SYSPATH') or die('No direct script access.');

class View_User_Register {

        public $title = 'Register';

        public function csrf()
        {
                return Security::token();
        }

}

