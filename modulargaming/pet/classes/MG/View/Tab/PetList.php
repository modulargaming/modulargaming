<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for user pet tab.
 *
 * @package    MG/Pet
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Tab_PetList extends Abstract_View_Tab {

	/**
	 * @var Model_User
	 */
	public $user;

	/**
	 * @var Model_User_Pet[]
	 */
	public $pets;

	public function pets()
	{
		$pets = array();
		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'src'    => URL::base().'media/image/pets/'.$pet->specie->id.'/'.$pet->colour->image,
				'href'   => Route::url('pet', array('name' => strtolower($pet->name))),
				'name'   => $pet->name,
				'specie' => $pet->specie->name,
				'colour' => $pet->colour->name
			);
		}
		return $pets;
	}

	public function user() {
		return array(
			'username' => $this->user->username
		);
	}

}
