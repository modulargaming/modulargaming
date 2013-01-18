<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Profile extends Abstract_View {

	public $title = 'Pet name';

	public function pet()
	{
		$pet = $this->pet;

		return array(
			'id'      => $pet->id,
			'created' => Date::format($pet->created),
			'name'    => $pet->name,
			'gender'  => $pet->gender,
			'specie'    => $pet->specie,
			'colour'  => $pet->colour,
			'hunger'  => $pet->hunger,
			'happiness'  => $pet->happiness,
			'user'    => array(
				'username' => $pet->user->username,
				'id'   => $pet->user->id,
				'href' => Route::url('user.view', array(
					'id'     => $pet->user->id,
				)),
			),
		);
	}


}
