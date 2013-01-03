<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Profile extends View_Base {

	public $title = 'Pet name';

	public function pet()
	{
		$pet = $this->pet;

		return array(
			'id'      => $pet->id,
			'created' => Date::format($pet->created),
			'name'    => $pet->name,
			'gender'  => $pet->gender,
			'race'    => $pet->race,
			'colour'  => $pet->colour,
			'user'    => array(
				'username' => $pet->user->username,
				'id'   => $pet->user->id,
				'href' => Route::url('user', array(
					'action' => 'view',
					'id'     => $pet->user->id,
				)),
			),
			'race' => array(
				'name' => $pet->race->name,
				'id'   => $pet->race->id,
			),
		);
	}


}
