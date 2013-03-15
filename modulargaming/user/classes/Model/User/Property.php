<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Property extends ORM {

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User'
		)
	);

	protected $_serialize_columns = array(
		'value'
	);



}
