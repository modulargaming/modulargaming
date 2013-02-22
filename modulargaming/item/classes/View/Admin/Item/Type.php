<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Type extends Abstract_View_Admin {

	public $title = 'Items';
	/**
	 * How many item types to display on 1 page
	 * @var integer
	 */
	public $paginate_max = 20;

	/**
	 * simplify item type data
	 * @return array
	 */
	public function types()
	{
		$list = array();

		if(count($this->types) > 0)
		{
			foreach ($this->types as $type)
			{
				$list[] = array (
					'id'     => $type->id,
					'name'   => $type->name,
				);
			}
		}

		return $list;
	}

	/**
	 * A list of item commands
	 * @return array
	 */
	public function commands()
	{
		$commands = Item::list_commands();
		$list_c = array();

		foreach ($commands as $cmd) {
			$name = str_replace(Kohana::include_paths(), '', $cmd);
			$name = 'Item_Command_'.str_replace(array('\\', '/'), '_', $name);
			$command = new $name;

			if($command->is_default() == FALSE)
			{
				$list_c[] = array(
					'name' 	=> str_replace('_', ' - ', $name),
					'value' => $name
				);
			}
		}

		return $list_c;
	}
}
