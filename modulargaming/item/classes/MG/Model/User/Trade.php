<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_User_Trade extends ORM {

		protected $_table_columns = array(
		'id'          => NULL,
		'user_id'       => NULL,
		'created'    => NULL,
		'description'    => NULL
		);

		protected $_belongs_to = array(
			'user' => array(
				'model'       => 'User',
				'foreign_key' => 'user_id'
			),
		);

		protected $_has_many = array(
			'bids' => array(
				'model'       => 'User_Trade_Bid',
				'foreign_key' => 'trade_id'
			)
		);

		protected $_created_column = array(
			'column' => 'created',
			'format' => TRUE
		);

		protected $_load_with = array('user');

		public function rules()
		{
			return array(
				'description' => array(
					array('max_length', array(':value', 144)),
				),
			);
		}

		public function items()
		{
			return Item::location('trade.lot', FALSE, $this->id, $this->user)
				->find_all();
		}

	} // End User_Trade Model
