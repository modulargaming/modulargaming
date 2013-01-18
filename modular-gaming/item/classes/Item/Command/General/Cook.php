<?php
class Item_Command_General_Cook extends Item_Command {
	public function build_form(){
		return array(
			'title' => 'Recipe', 
			'fields' => array(
				array(
					'input' => array(
						'name' => 'cook', 'class' => 'recipe-search'
					)
				)
			)	
		);
	}
	
	public function validate($param) {
		$recipe = ORM::factory('Item_Recipe')
			->where('name', '=', $param)
			->find();
		
		return $recipe->loaded();
	}
	
	public function perform($item, $data) {
		return null;
	}
}