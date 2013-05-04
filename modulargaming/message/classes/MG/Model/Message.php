<?php defined('SYSPATH') OR die('No direct access allowed.');

class MG_Model_Message extends ORM
{

	protected $_table_columns = array(
		'id'          => NULL,
		'sender_id'       => NULL,
		'receiver_id'    => NULL,
		'created'    => NULL,
		'subject'     => NULL,
		'content'  => NULL,
		'read'  => NULL,
		'sent'  => NULL
	);

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE,

	);

	protected $_belongs_to = array(
		'receiver' => array(
			'model' => 'User',
		),
		'sender' => array(
			'model' => 'User',
		),
	);

	public function rules()
	{
		return array(
			'receiver_id' => array(
				array('not_empty'),
			),
			'subject' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'content' => array(
				array('not_empty'),
				array('max_length', array(':value', 1024)),

			),
		);
	}

	public function filters()
	{
		return array(
			'content' => array(
				array('Security::xss_clean'),
			),
		);
	}

	public function create_message($values, $expected)
	{
		// Validation for id
		$extra_validation = Validation::Factory($values)
			->rule('receiver_id', 'Model_User::user_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}



} // End Messages Model
