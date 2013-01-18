<?php
class Item_Command_Pet_Transform extends Item_Command_Pet {
	public function build_form($name){
		return array(
			'title' => 'Pet specie', 
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-small pet-specie-search'
					)
				)
			)	
		);
	}
	
	public function validate($param) {
		$specie = ORM::factory('Pet_Specie')
			->where('pet_specie.name', '=', $param)
			->find();
		
		return $specie->loaded();
	}
	
	public function perform($item, $data) {
		return null;
	}
}