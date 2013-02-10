<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Index extends Abstract_View_Admin {

	public $title = 'Items';
	
	/**
	 * A list of item types
	 * @var array
	 */
	public $item_types = array();
	
	/**
	 * How commands are added to a form
	 * @var array
	 */
	public $input_commands = array();
	
	/**
	 * Build the navigational command menu
	 * @var array
	 */
	public $menu_commands = array();
	
	/**
	 * Command definition for javascript interface, so it knows how to deal with the commands
	 * @var array
	 */
	public $command_definitions = array();
	
	public $image = array('width' => 0, 'heigth' => 0);

	/**
	 * Format the list of item types
	 * @return array
	 */
	public function item_types()
	{
		$list = array();

		if(count($this->item_types) > 0)
		{
			foreach ($this->item_types as $type)
			{
				$list[] = array(
					'id' => $type->id,
					'name' => $type->name,
					'command' => $type->default_command
				);
			}
		}

		return $list;
	}
}
