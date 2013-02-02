<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Index extends Abstract_View_Admin {

	public $title = 'Items';
	
	/**
	 * Max amount of items to display per page
	 * @var integer
	 */
	public $paginate_max = 20;
	
	/**
	 * A list of item types
	 * @var array
	 */
	public $item_types = array();
	
	/**
	 * Contains Items
	 * @var array
	 */
	public $items = array();
	
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

	/**
	 * Format the list of item types
	 * @return array
	 */
	public function item_types()
	{
		$list = array();

		if(count($this>item_types) > 0)
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
	
	/**
	 * Simplify the item's format
	 * @return array
	 */
	public function items(){
		$list = array();
		
		if(count($this->items) > 0)
		{
			foreach($this->items as $item) {
				$status = false;
				
				if($item->status != 'released')
				{
					$status = array('name' => $item->status);
				}
				
				$list[] = array (
					'id' => $item->id,
					'img' => $item->img(),
					'name' => $item->name,
					'type' => $item->type->name,
					'status' => $status
				);
			}
		}
		
		return $list;
	}
}
