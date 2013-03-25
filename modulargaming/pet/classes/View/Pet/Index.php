<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Index extends Abstract_View_Pet {

	public $title = 'Your pets';

	public $pets;

	public function pets()
	{
		$pets = array();
		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'id'  => $pet->id,
				'src' => URL::base().'assets/img/pets/'.$pet->specie->id.'/'.$pet->colour->image,
				'href' => Route::url('pet', array('name' => strtolower($pet->name))),
				'name' => $pet->name,
				'specie' => $pet->specie->name,
				'colour' => $pet->colour->name,
				'username' => $pet->user->username
			);
		}
		return $pets;
	}
}
