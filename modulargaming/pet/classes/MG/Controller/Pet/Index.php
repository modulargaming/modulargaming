<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for pet index
 *
 * @package    MG/Pet
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Pet_Index extends Abstract_Controller_Frontend {

	protected $protected = TRUE;

	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				if ($this->request->post('active'))
				{
					$pet = ORM::factory('User_Pet')
					->where('user_pet.id', '=', $this->request->post('active'))
					->where('user_id', '=', $this->user->id)
					->find();
					$pet->active = time();
					$pet->save();
					Hint::success($pet->name . ' is now your active pet.');
				}
				if ($this->request->post('abandon'))
				{
					$pet = ORM::factory('User_Pet')
					->where('user_pet.id', '=', $this->request->post('abandon'))
					->where('user_id', '=', $this->user->id)
					->find();
					$pet->user_id = NULL;
					$pet->abandoned = time();
					$pet->save();
					Hint::success('You have abandoned ' . $pet->name . '.');
				}
				$this->redirect(Route::get('pets')->uri());
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_Pet_Index;
		$pets = ORM::factory('User_Pet')
		->where('user_id', '=', $this->user->id)
		->order_by('active', 'desc');

		$paginate = Paginate::factory($pets)
			->execute();
		$this->view->pagination = $paginate->render();
		$this->view->pets = $paginate->result();
		$this->view->pets_count = count($pets);
		$this->view->href = array(
				'create' => Route::url('pet.create'),
				'adopt' => Route::url('pet.adopt'),
			);
	}

}
