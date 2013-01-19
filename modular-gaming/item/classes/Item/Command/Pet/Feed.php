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
		$pet = $data['pet'];
		
		if($pet->hunger == 100)
			return $pet->name.' is already full';
		else
		{
			$level = $pet->hunger + $data['param'];
			
			if($level > 100)
				$pet->hunger = 100;
			else 
				$pet->hunger = $level;
			
			$pet->save();
			
			return $pet->name.' has been fed '. $item->name;
		}
	}
}