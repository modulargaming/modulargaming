<?php defined('SYSPATH') OR die('No direct access allowed.');

	class MG_Model_Item_Recipe extends ORM {

		protected $_table_columns = array(
		'id'          => NULL,
		'name'       => NULL,
		'description'    => NULL,
		'crafted_item_id'    => NULL
		);

		protected $_has_many = array(
			'materials' => array(
				'model'       => 'Item_Recipe_Material',
				'foreign_key' => 'item_recipe_id'
			),
		);

		protected $_belongs_to = array(
			'item' => array(
				'model'       => 'Item',
				'foreign_key' => 'crafted_item_id'
			),
		);

		protected $_load_with = array('item', 'materials');

		public function rules()
		{
			return array(
				'name'            => array(
					array('not_empty'),
					array('min_length', array(':value', 3)),
					array('max_length', array(':value', 50)),
				),
				'description'     => array(
					array('not_empty'),
					array('max_length', array(':value', 200)),
				),
				'crafted_item_id' => array(
					array('not_empty'),
					array('digit'),
				),
			);
		}

		static public function validate_material_names($validation, $materials, $name_key = 'name')
		{
			$mat_names = array();

			foreach ($materials as $material)
			{
				$mat_names[] = $material[$name_key];
			}

			try
			{
				$item = ORM::factory('Item')
					->where('item.name', 'IN', $mat_names)
					->find_all();
			} catch (ORM_Validation_Exception $e)
			{
				$validation->error('materials', $e->errors());

				return FALSE;
			}

			return TRUE;
		}

		static public function validate_material_amounts($validation, $materials, $amount_key = 'amount', $name_key = 'name')
		{
			$status = TRUE;

			foreach ($materials as $material)
			{
				if (!Valid::digit($material[$amount_key]))
				{
					$status = $false;
					$validation->error('materials', $material[$name_key] . '\'s amount should be a number.');
				}
			}

			return $status;
		}

	} // End Item Recipe Model
