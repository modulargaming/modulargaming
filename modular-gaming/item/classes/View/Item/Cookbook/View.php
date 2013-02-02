<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Cookbook_View extends Abstract_View_Cookbook {

	public $title = 'Cook book';
	
	public function recipe() {
		return array(
			'name' => $this->recipe->name,
			'img' => $this->recipe->item->img(),	
		);
	}
	
	public function materials() {		
		foreach($this->materials as $key => $material) {
			$this->materials[$key]['color'] = ($material['amount_needed'] == $material['amount_owned']) ? 'green' : 'red';
		}
		
		return $this->materials;
	}
	
	public function collected() {
		if($this->collected == false)
			return false;
		else {
			return array(
				'url' => URL::site(Route::get('item.cookbook.complete')->uri(array('id' => $this->id))),
				'id' => $this->id,
				'csrf' => $this->csrf()		
			);
		}
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Cook '.$this->recipe->item->name,
				'href'  => Route::url('item.cookbook.view', array('id' => $this->id))
			)
		));
	}
}
