<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Item extends ORM {

	protected $_belongs_to = array(
		'type' => array(
			'model' => 'Item_Type',
			'foreign_key' => 'type_id'
		),
	);

	protected $_serialize_columns = array('commands');
	
	protected $_load_with = array('type');

	public function rules()
	{
		//@todo validate the command property
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
			'description' => array(
				array('not_empty'),
			),
			'image' => array(
				array('not_empty'),
				array('max_length', array(':value', 200)),
			),
			'status' => array(
				array('not_empty'),
				array('in_array', array(':value', array('draft', 'released', 'retired'))),
			),
			'commands' => array(
				array('not_empty'),
				array('Item::validate_commands', array(':validation', ':value'))		
			)
		);
	}
	
	/**
	 * Create the url to the item's image
	 * @return string
	 */
	public function img(){
		return URL::base().'assets/img/items/'.$this->type->img_dir.$this->image;
	}
	
	/**
	 * Check if the item isn't a draft or retired.
	 * @return boolean
	 */
	public function in_circulation(){
		return ($this->status == 'released');
	}
	
	/**
	 * Get the item's name based on an amount
	 * 
	 * @param integer $amount
	 * @return string
	 */
	public function name($amount) {
		if($amount > 1)
			return $amount . ' ' . Inflector::plural($this->name, $amount);
		else
			return $amount . ' ' . $this->name;
	}

} // End Item Model
