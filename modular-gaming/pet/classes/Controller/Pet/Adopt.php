<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Pet_Adopt extends Abstract_Controller_Frontend {
	protected $protected = TRUE;
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				if ($this->request->post('adopt'))
				{
					if(Model_User_Pet::pet_limit($this->user->id))
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
		Breadcrumb::add('Your pets', Route::url('pets'));
		Breadcrumb::add('Adopt a pet', Route::url('pet.adopt'));
		$this->view = new View_Pet_Adopt;
		$pets = ORM::factory('User_Pet')
		->where('user_id', '=', 0)
		->order_by('abandoned', 'desc')
		->find_all();
		$array = array();
		foreach ($pets as $pet)
		{
			$array[] = $pet;
		}
		$this->view->pets = $array;
		$this->view->pets_count = count($array);
		$this->view->href = array(
				'create' => Route::url('pet.create'),
			);
	}

}
