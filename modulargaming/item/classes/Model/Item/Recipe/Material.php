<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Item_Recipe_Material extends ORM {
	protected $_belongs_to = array(
		'recipe' => array(
			'model' => 'Item_Recipe',
			'foreign_key' => 'item_recipe_id'
		),
		'item' => array(
			'model' => 'Item',
			'foreign_key' => 'item_id'
		),
	);
	
	protected $_load_with = array('item');
	
	public function rules()
	{
		return array(
			'item_recipe_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'item_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'amount' => array(
				array('not_empty'),
				array('digit'),
			),
		);
	}

} // End Item Recipe Material Model
