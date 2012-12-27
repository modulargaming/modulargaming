<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Topic extends ORM {

	protected $_belongs_to = array(
		'category' => array(
			'model'   => 'Forum_Category',
		)
	);

        protected $_has_many = array(
                'posts' => array(
                        'model'       => 'Forum_Post',
                        'foreign_key' => 'topic_id',
                ),
        );


}
