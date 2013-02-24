<?php defined('SYSPATH') OR die('No direct script access.');
 
class PetEvents {

	public static function user_profile(Tab_Container $tabs)
	{
		$pets = new Tab('Pets');

		$tabs->add_tab($pets);
	}

}
