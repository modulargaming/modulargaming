<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Modal_Pet extends Abstract_Controller_Modal {

	public function action_view()
	{
		$this->view = new View_Admin_User_Modal_Pets;
		$this->view->pets = ORM::factory('User_Pet')
			->where('user_id', '=', $this->_player->id)
			->find_all();

		$this->view->data_sources = array(
			array(
				'id' => 'save',
				'url' => Route::url('admin.pet.modal.save', array('user_id' => $this->_player->id, 'pet_id' => 0))
			)
		);
	}

	public function action_save()
	{
		$this->view = null;

		if ($this->request->method() == HTTP_Request::POST)
		{
			try
			{
				$pet = ORM::factory('User_Pet', $this->request->param('pet_id'))
					->values($this->request->post())
					->save();

				Journal::log('admin.user.pet.'.$pet->id, 'admin', ':username edited :pet a message through the admin', array(':pet' => $pet->name));

				$this->response->headers('Content-Type', 'application/json');
				$this->response->body(json_encode(array(
					'status' => 'success'
				)));
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->response->headers('Content-Type', 'application/json');
				$this->response->body(json_encode(array(
					'status' => 'error',
					'errors' => $e->errors('models')
				)));
			}

		}
	}
}
