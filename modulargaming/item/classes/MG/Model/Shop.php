<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_Shop extends ORM {


		protected $_table_columns = array(
		'id'          => NULL,
		'title'       => NULL,
		'npc_img'    => NULL,
		'npc_text'    => NULL,
		'stock_type'      => NULL,
		'stock_cap'     => NULL,
		'status' => NULL
		);

		public function rules()
		{
			return array(
				'title'        => array(
					array('not_empty'),
					array('max_length', array(':value', 60)),
				),
				'npc_img' => array(
					array('not_empty'),
					array('max_length', array(':value', 100)),
				),
				'npc_text'       => array(
					array('not_empty'),
					array('max_length', array(':value', 144)),
				),
				'stock_type'      => array(
					array('not_empty'),
					array('in_array', array(':value', array('restock', 'steady'))),
				),
				'status'      => array(
					array('not_empty'),
					array('in_array', array(':value', array('closed', 'open'))),
				),
				'stock_cap'    => array(
					array('not_empty'),
					array('digit')
				)
			);
		}

		/**
		 * Create the url to the npc's image
		 * @return string
		 */
		public function img()
		{
			return URL::site('media/image/npc/shop/' . $this->npc_img);
		}

	} // End Shop Model
