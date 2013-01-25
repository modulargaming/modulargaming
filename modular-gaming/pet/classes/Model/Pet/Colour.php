<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Colour extends ORM {

	protected $_has_many = array(
		'pets' => array(
			'model' => 'User_Pet',
			'foreign_key' => 'colour_id',
		),
	);

}
