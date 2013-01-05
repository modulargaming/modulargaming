<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Pet_Index extends Controller_Frontend {
	protected $protected = TRUE;
	public function action_index()
	{
		if ($_POST)
		{
			try
			{
				if (array_key_exists('active', $_POST))
				{
					$pet = ORM::factory('Pet')
					->where('id', '=', $_POST['active'])
					->where('user_id', '=', $this->user->id)
					->find();
					$pet->active = time();
					$pet->save();
					Hint::success($pet->name . ' is now your active pet.');
				}
				if (array_key_exists('abandon', $_POST))
				{
					$pet = ORM::factory('Pet')
					->where('id', '=', $_POST['abandon'])
					->where('user_id', '=', $this->user->id)
					->find();
					$pet->user_id = 0;
					$pet->abandoned = time();
					$pet->save();
					Hint::success('You have abandoned ' . $pet->name . '.');
				}
				$this->redirect('pets');
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}
		Breadcrumb::add('Your pets', Route::url('pets'));
		$this->view = new View_Pet_Index;
		$pets = ORM::factory('Pet')
		->where('user_id', '=', $this->user->id)
		->order_by('active', 'desc')
		->find_all();
		$array = array();
		foreach ($pets as $key => $value)
		{
			$array[] = array(
				'id' => $value->id,
				'user_id' => $value->user_id,
				'name' => $value->name,
				'active' => ($key ? 0 : $value->active),
				'link' => Route::url('pet', array('name' => strtolower($value->name))),
				'race' => $value->race,
				'colour' => $value->colour,
				'created' => Date::format($value->created),
			);
		}
		$this->view->pets = $array;
		$this->view->pets_count = count($array);
		$this->view->href = array(
				'create' => Route::url('pet/create'),
				'adopt' => Route::url('pet/adopt'),
			);
	}

}