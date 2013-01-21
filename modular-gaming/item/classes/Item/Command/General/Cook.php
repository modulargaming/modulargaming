<?php
class Item_Command_General_Cook extends Item_Command {
	protected function _build($name){
		return array(
			'title' => 'Recipe', 
			'search' => 'recipe',
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
		$recipe = ORM::factory('Item_Recipe')
			->where('item_recipe.name', '=', $param)
			->find();
		
		return $recipe->loaded();
	}
	
	public function perform($item, $param, $data=null) {
		return null;
	}
}