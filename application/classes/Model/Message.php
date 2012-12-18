<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Message extends ORM
{
    public function rules()
    {
        return array(
            'subject' => array(
                array('not_empty'),
                array('max_length', array(':value', 255)),
            ),
            'text' => array(
                array('not_empty'),
            ),
        );
    }
} // End Messages Model
