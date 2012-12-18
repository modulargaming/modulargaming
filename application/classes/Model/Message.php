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

	public function create_message($values, $expected)
        {
                // Validation for id
                //$extra_validation = Model_Message::get_id_validation($values)
                //        ->rule('to_id', 'valid');

                //return $this->values($values, $expected)->create($extra_validation);
                return $this->values($values, $expected)->create();
        }



} // End Messages Model
