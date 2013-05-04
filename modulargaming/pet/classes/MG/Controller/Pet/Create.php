<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for pet create
 *
 * @package    MG/Pet
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Pet_Create extends Abstract_Controller_Frontend {

	protected $protected = TRUE;
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$array = Arr::merge($this->request->post(), array(
					'user_id' => $this->user->id,
					'active' => time(),
				));

				$new_pet = ORM::factory('User_Pet')
					->create_pet($array, array(
						'user_id',
						'specie_id',
						'colour_id',
						'gender',
						'name',
						'active',
					));
				Hint::success('You have created a pet named '.$new_pet->name);
				$this->redirect(Route::get('pets')->uri());
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$species = ORM::factory('Pet_Specie')->find_all();
		$this->view = new View_Pet_Create;

		$colours = ORM::factory('Pet_Colour')->where('locked', '=', 0)->find_all();
		$this->view->colours = $colours;

		$this->view->species = $species;

		$this->view->default_specie = $species[0]->dir;
		$this->view->default_colour = $colours[0]->image;

		$this->view->href = array(
			'adopt' => Route::url('pet.adopt'),
		);
	}

}
