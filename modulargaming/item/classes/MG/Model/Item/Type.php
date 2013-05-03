<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_Item_Type extends ORM {


		protected $_table_columns = array(
		'id'          => NULL,
		'name'       => NULL,
		'action'    => NULL,
		'default_command'    => NULL,
		'img_dir'      => NULL
		);


		public function rules()
		{
			return array(
				'name'            => array(
					array('not_empty'),
					array('max_length', array(':value', 50)),
				),
				'action'          => array(
					array('not_empty'),
					array('max_length', array(':value', 200)),
				),
				'default_command' => array(
					array('not_empty'),
					array('max_length', array(':value', 100)),
				),
				'img_dir'         => array(
					array('not_empty'),
					array('max_length', array(':value', 50)),
				),
			);
		}

		public function filters()
		{
			return array(
				'img_dir' => array(
					array('Item::filter_type_dir')
				)
			);
		}

	} // End Item Type Model
