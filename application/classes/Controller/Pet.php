<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Pet extends Controller_Frontend {

	public function action_index()
	{
		$pets = $this->user->pets->order_by('active', 'desc')->find_all();
		if ($_POST)
		{
			$post = $this->request->post();
			if($post['action'] == 'active')
			{
				try
				{
					foreach($pets as $pet)
					{
						if($pet->id == $post['pet_id'])
						{
							$pet->user->active = 1;
							Hint::success($pet->name.' is now your active pet.');
						}
						else
						{
							$pet->user->active = 0;
						}
						$pet->user->save();
					}
					$this->redirect('pet');
				}
				catch (ORM_Validation_Exception $e)
				{
					Hint::error($e->errors('models'));
				}
			}
			if($post['action'] == 'abandon')
			{
				try
				{
					foreach($pets as $pet)
					{
						if($pet->id == $post['pet_id'])
						{
							if($pet->user->active == 1)
							{
								$pet->user->active = 0;
								$active_pets = $this->user->pets->order_by('id', 'desc')->find_all();
								$new_active_pet = 0;
								foreach($active_pets as $active_pet)
								{
									if($active_pet->id != $pet->id && $new_active_pet == 0)
									{
										$new_active_pet = 1;
										$active_pet->user->active = 1;
										$active_pet->user->save();
									}
								}
							}
							$pet->user->user_id = 0;
							$pet->user->save();
							Hint::success('You have abandoned '.$pet->name);
						}
					}
					$this->redirect('pet');
				}
				catch (ORM_Validation_Exception $e)
				{
					Hint::error($e->errors('models'));
				}
			}
		}
		$this->view = new View_Pet_Index;
		$this->view->pets = $pets;
	}
	
	public function action_adopt()
	{
		$this->view = new View_Pet_Adopt;
	}

	public function action_create()
	{
		$this->view = new View_Pet_Create;
	}
	
} // End Pet
