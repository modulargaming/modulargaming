<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Post extends ORM {


        protected $_belongs_to = array(
                'topic' => array(
                        'model'   => 'Forum_Topic',
                )
        );


}

