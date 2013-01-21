<?php
class Item_Command_Pet_Play extends Item_Command_Pet {
	protected function _build($name){
		return array(
			'title' => 'Pet mood', 
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
	
	public function perform($item, $param, $data=null) {
		$pet = $data['pet'];
		
		if($pet->happiness == 100)
			return $pet->name.' is already too happy';
		else
		{
			$level = $pet->happiness + $param;
			
			if($level > 100)
				$pet->happiness = 100;
			else 
				$pet->happiness = $level;
			
			$pet->save();
			
			return $pet->name.' played with '. $item->name;
		}
	}
}