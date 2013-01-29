<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pet_Colour extends Abstract_Controller_Admin {
	
	public function action_index()
	{

		if ( ! $this->user->can('Admin_Pet_Colour_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pet colour index');
		}

		$colours = ORM::factory('Pet_Colour')
			->find_all();

		$this->view = new View_Admin_Pet_Colour_Index;
		$this->_load_assets(Kohana::$config->load('assets.admin_pet.colour'));
		$this->_nav('pet', 'colour');
		$this->view->colours = $colours->as_array();
	}


	public function action_retrieve() {
		$this->view = null;
	
		$item_id = $this->request->query('id');
	
		if($item_id == null) 
		{
			$colour = ORM::factory('Pet_Colour')
			->where('pet_colour.name', '=', $this->request->query('name'))
			->find();
		}
		else
			$colour = ORM::factory('Pet_Colour', $item_id);
	
		$list = array(
			'id' => $colour->id,
			'name' => $colour->name,
			'description' => $colour->description,
			'image' => $colour->image,
			'locked' => $colour->locked
		);
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode($list));
	}
	
	public function action_save(){
		$values = $this->request->post();
		$this->view = null;
	
		if($values['id'] == 0)
			$values['id'] = null;
	
		$this->response->headers('Content-Type','application/json');
	
		try {
			$colour = ORM::factory('Pet_Colour', $values['id']);
			$colour->values($values, array('name', 'description', 'locked', 'image'));
			$colour->save();
	
			$data = array(
				'action' => 'saved',
				'row' => array(
					'id' => $colour->id,
					'name' => $colour->name,
					'locked' => $colour->locked
				)
			);
			$this->response->body(json_encode($data));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = array();
	
			$list = $e->errors('models');
	
			foreach($list as $field => $er){
				if(!is_array($er))
					$er = array($er);
	
				$errors[] = array('field' => $field, 'msg' => $er);
			}
	
			$this->response->body(json_encode(array('action' => 'error', 'errors' => $errors)));
		}
	}
	
	public function action_delete(){
		$this->view = null;
		$values = $this->request->post();
	
		$colour = ORM::factory('Pet_Colour', $values['id']);
		$colour->delete();
	
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
