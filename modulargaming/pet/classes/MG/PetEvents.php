<?php defined('SYSPATH') OR die('No direct script access.');
 
class MG_PetEvents {

	public static function user_profile(Model_User $user, Tabs $tabs)
	{
		$pets = ORM::factory('User_Pet')
			->where('user_id', '=', $user->id)
			->order_by('active', 'desc')
			->find_all();

		$tab = new Tab('Pets');
		$tab->add_content(new Tab_PetList($user, $pets->as_array()));

		$tabs->add_tab($tab);
	}

}
