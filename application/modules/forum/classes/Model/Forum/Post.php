<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Forum_Post extends ORM {


        protected $_created_column = array(
                'column' => 'created',
                'format' => TRUE
        );

        protected $_belongs_to = array(
                'topic' => array(
                        'model'   => 'Forum_Topic',
                )
        );


}

