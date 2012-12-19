<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Message extends ORM
{

	protected $_has_one = array(
	'receiver_id' => array(
        'model'       => 'User',
        'foreign_key' => 'id',
	),
	'sender_id' => array(
        'model'       => 'User',
        'foreign_key' => 'id',
	),
);


	public function rules()
	{
		return array(
			'receiver_id' => array(
				array('not_empty'),
				array('max_length', array(':value', 6)),
				array('numeric'),
			),
			'subject' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'text' => array(
				array('not_empty'),
				array('max_length', array(':value', 1024)),

			),
		);
	}

	public function create_message($values, $expected)
	{
		// Validation for id
		//$extra_validation = Model_Message::get_id_validation($values)
		//        ->rule('receiver_id', 'valid');

		//return $this->values($values, $expected)->create($extra_validation);
		return $this->values($values, $expected)->create();
	}



} // End Messages Model
