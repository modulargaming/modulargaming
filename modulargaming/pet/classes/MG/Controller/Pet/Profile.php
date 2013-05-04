<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for pet profile
 *
 * @package    MG/Pet
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Pet_Profile extends Abstract_Controller_Frontend {

	protected $protected = TRUE;
	public function action_index()
	{
		$pet = ORM::factory('User_Pet')
		->where('name', '=', $this->request->param('name'))
		->find();
		if (!$pet->loaded())
		{
			throw HTTP_Exception::factory('404', 'Pet not found');
		}

		$this->view = new View_Pet_Profile;

		$this->view->pet = $pet;
		$this->view->href = array(
				'create' => Route::url('pet.create'),
			);
	}

}
