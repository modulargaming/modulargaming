<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Message extends ORM
{

	protected $_belongs_to = array(
		'receiver' => array(
			'model'       => 'User',
		),
		'sender' => array(
			'model'       => 'User',
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
		//        ->rule('receiver', 'valid');

		//return $this->values($values, $expected)->create($extra_validation);
		return $this->values($values, $expected)->create();
	}



} // End Messages Model
