<?php
class Item_Command_Pet_Paint extends Item_Command_Pet {
	public function build_form($name){
		return array(
			'title' => 'Pet color', 
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-small pet-color-search'
					)
				)
			)	
		);
	}
	
	public function validate($param) {
		$color = ORM::factory('Pet_Colour')
			->where('name', '=', $param)
			->find();
		return $color->loaded();
	}
	
	public function perform($item, $data) {
		return null;
	}
}