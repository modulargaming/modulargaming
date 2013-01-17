<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Pet_Profile extends Abstract_Controller_Frontend {
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
		if ($pet->user_id == $this->user->id)
		{
			Breadcrumb::add('Your pets', Route::url('pets'));
		}
		else
		{
			if($pet->user_id)
			{
				Breadcrumb::add($pet->user->username . "'" . ($pet->user->username[strlen($pet->user->username)-1] == 's' ? '' : 's') . ' pets', '#');
			}
			else
			{
				Breadcrumb::add('Abandoned pets', Route::url('pets'));
			}
		}
		Breadcrumb::add($pet->name, Route::url('pet', array('name' => strtolower($pet->name))));
		$this->view = new View_Pet_Profile;

		$this->view->pet = $pet;
		$this->view->href = array(
				'create' => Route::url('pet.create'),
			);
	}

}
