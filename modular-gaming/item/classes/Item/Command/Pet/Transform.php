<?php
class Item_Command_Pet_Transform extends Item_Command_Pet {
	public function build_form(){
		return array(
			'title' => 'Pet specie', 
			'fields' => array(
				array(
					'input' => array(
						'name' => 'transform', 'class' => 'pet-specie-search'
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