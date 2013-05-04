<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for pet adopt
 *
 * @package    MG/Pet
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Pet_Adopt extends Abstract_Controller_Frontend {

	protected $protected = TRUE;
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				if ($this->request->post('adopt'))
				{
					if(Valid_Pet::limit($this->user->id))
					{
						$pet = ORM::factory('User_Pet')
						->where('user_pet.id', '=', $this->request->post('adopt'))
						->where('user_id', '=', 0)
						->find();
						$pet->user_id = $this->user->id;
						$pet->abandoned = time();
						$pet->save();
						Hint::success('You have adopted ' . $pet->name . '.');
						$this->redirect(Route::get('pets')->uri());
					}
					else
					{
						Hint::error('You already have 6 pets.');
					}
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Pet_Adopt;
		$pets = ORM::factory('User_Pet')
		->where('user_id', '=', 0)
		->order_by('abandoned', 'desc');

		$paginate = Paginate::factory($pets)
			->execute();

		$this->view->pagination = $paginate->render();
		$this->view->pets	= $paginate->result();
		$this->view->pets_count = count($pets);
		$this->view->href = array(
				'create' => Route::url('pet.create'),
			);
	}

}
