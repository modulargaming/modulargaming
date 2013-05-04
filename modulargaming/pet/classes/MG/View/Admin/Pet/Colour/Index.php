<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_Pet_Colour_Index extends Abstract_View_Admin {

	public $title = 'Pet colours';

	public function colours()
	{
		$colours = array();

		foreach ($this->colours as $colour)
		{
			$colours[] = array(
				'id'          => $colour->id,
				'name'        => $colour->name,
				'description' => $colour->description,
				'locked'      => (boolean) $colour->locked
			);
		}

		return $colours;
	}

}
