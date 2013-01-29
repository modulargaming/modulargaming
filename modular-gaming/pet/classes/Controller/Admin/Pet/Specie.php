<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Pet_Specie extends Controller_Admin_Pet {
	
	public function action_index()
	{

		if ( ! $this->user->can('Admin_Pet_Specie_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin pets index');
		}

		$species = ORM::factory('Pet_Specie')
			->find_all();

		$this->view = new View_Admin_Pet_Specie_Index;
		$this->_load_assets(Kohana::$config->load('assets.admin_pet.specie'));
		$this->_nav('pet', 'specie');
		$this->view->species = $species->as_array();
	}

	public function action_retrieve() {
		$this->view = null;
	
		$item_id = $this->request->query('id');
	
		if($item_id == null) 
		{
			$specie = ORM::factory('Pet_Specie')
			->where('pet_specie.name', '=', $this->request->query('name'))
			->find();
		}
		else
			$specie = ORM::factory('Pet_Specie', $item_id);
	
		$list = array(
			'id' => $specie->id,
			'name' => $specie->name,
			'description' => $specie->description,
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
			$specie = ORM::factory('Pet_Specie', $values['id']);
			$specie->values($values, array('name', 'description'));
			$specie->save();
				
			$data = array(
					'action' => 'saved',
					'row' => array(
						'id' => $specie->id,
						'name' => $specie->name,
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
	
		$specie = ORM::factory('Pet_Specie', $values['id']);
		$specie->delete();
	
		$this->response->headers('Content-Type','application/json');
		$this->response->body(json_encode(array('action' => 'deleted')));
	}
}
