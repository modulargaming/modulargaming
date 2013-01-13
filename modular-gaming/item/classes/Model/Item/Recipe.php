<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Item_Recipe extends ORM {

	protected $_has_many = array(
		'materials' => array(
			'model' => 'Item_Recipe_Material',
			'foreign_key' => 'item_recipe_id'
		),
	);
	
	protected $_belongs_to = array(
		'item' => array(
			'model' => 'Item',
			'foreign_key' => 'item_id'
		),
	);
	
	protected $_load_with = array('item', 'materials');
	
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 3)),
				array('max_length', array(':value', 50)),
			),
			'description' => array(
				array('not_empty'),
				array('max_length', array(':value', 200)),
			),
			'crafted_item_id' => array(
				array('not_empty'),
				array('digit'),
			),
		);
	}

} // End Item Recipe Model
