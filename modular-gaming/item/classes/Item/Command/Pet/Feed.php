<?php
class Item_Command_Pet_Feed extends Item_Command_Pet {
	public function build_form($name){
		return array(
			'title' => 'Pet hunger', 
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-mini'
					)
				)
			)	
		);
	}
	
	public function validate($param) {
		return (Valid::digit($param) && $param > 0);
	}
	
	public function perform($item, $data) {
		return null;
	}
}