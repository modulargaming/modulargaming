<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_Shop_Restock extends ORM {


		protected $_table_columns = array(
		'id'          => NULL,
		'shop_id'       => NULL,
		'item_id'    => NULL,
		'frequency'    => NULL,
		'next_restock'      => NULL,
		'min_price'     => NULL,
		'max_price'  => NULL,
		'min_amount' => NULL,
		'max_amount' => NULL,
		'cap_amount' => NULL
		);
		protected $_belongs_to = array(
			'item' => array(
				'model' => 'Item',
				'foreign_key' => 'item_id'
			),
			'shop' => array(
				'model' => 'Shop',
				'foreign_key' => 'shop_id'
			)
		);

		protected $_load_with = array('item');

		public function rules()
		{
			return array(
				'min_price'    => array(
					array('not_empty'),
					array('digit')
				),
				'max_price'    => array(
					array('not_empty'),
					array('digit')
				),
				'min_amount'    => array(
					array('not_empty'),
					array('max_length', array(':value', 3)),
					array('digit')
				),
				'max_amount'    => array(
					array('not_empty'),
					array('max_length', array(':value', 3)),
					array('digit')
				),
				'cap_amount'    => array(
					array('not_empty'),
					array('max_length', array(':value', 3)),
					array('digit')
				)
			);
		}

	} // End Shop Restock Model
