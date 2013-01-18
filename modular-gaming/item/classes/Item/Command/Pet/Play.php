<?php
class Item_Command_Pet_Play extends Item_Command_Pet {
	public function build_form(){
		return array(
			'title' => 'Pet mood', 
			'fields' => array(
				array(
					'input' => array(
						'name' => 'play', 'class' => 'input-mini'
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