<?php
class Item_Command_User_Item extends Item_Command {
	protected function _build($name){
		return array(
			'title' => 'Item', 
			'search' => 'item',
			'multiple' => 1,
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
		$recipe = ORM::factory('Item')
			->where('item.name', '=', $param)
			->find();
		
		return $recipe->loaded();
	}
	
	public function perform($item, $param, $data=null) {
		return null;
	}
}