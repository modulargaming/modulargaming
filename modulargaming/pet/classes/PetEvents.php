<?php defined('SYSPATH') OR die('No direct script access.');
 
class PetEvents {

	public static function user_profile(Model_User $user, Tab_Container $tabs)
	{
		$pets = ORM::factory('User_Pet')
			->where('user_id', '=', $user->id)
			->order_by('active', 'desc')
			->find_all();

		$tab = new Tab('Pets');
		$tab->add_content(new Tab_Content_PetList($pets->as_array()));

		$tabs->add_tab($tab);
	}

}
