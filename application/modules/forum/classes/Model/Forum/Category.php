<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Category extends ORM {

	protected $_has_many = array(
		'topics' => array(
			'model'       => 'Forum_Topic',
			'foreign_key' => 'category_id',
		),
	);

}