<?php
class Item_Command_Pet_Paint extends Item_Command_Pet {
	protected function _build($name){
		return array(
			'title' => 'Pet color', 
			'search' => 'pet-color',
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-small search'
					)
				)
			)	
		);
	}
	
	public function validate($param) {
		$color = ORM::factory('Pet_Colour')
			->where('pet_colour.name', '=', $param)
			->find();
		return $color->loaded();
	}
	
	public function perform($item, $param, $data=null) {
		return null;
	}
}