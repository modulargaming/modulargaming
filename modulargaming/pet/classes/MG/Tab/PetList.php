<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Pet
 * @category   Tab
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Tab_PetList extends Abstract_Tab {

	public function __construct(Model_User $user, array $pets)
	{
		$this->view = new View_Tab_PetList;
		$this->view->user = $user;
		$this->view->pets = $pets;
	}

}
