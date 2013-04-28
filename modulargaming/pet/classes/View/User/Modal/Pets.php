<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Modal_Pets {

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public function pets() {
		$list = array();
		$first = true;

		foreach($this->pets as $pet)
		{
			$list[] = array(
				'id' => $pet->id,
				'name' => $pet->name,
				'active' => $first
			);
			$first = false;
		}
		return $list;
	}

	public function pet_info() {
		$list = array();
		$first = true;

		foreach($this->pets as $pet)
		{
			$list[] = array(
				'id' => $pet->id,
				'name' => $pet->name,
				'active' => $first,
				'genders' => $this->gender($pet->gender),
				'colours' => $this->colour($pet->specie, $pet->colour_id),
				'species' => $this->specie($pet->specie_id),
				'hunger' => $pet->hunger,
				'happiness' => $pet->happiness
			);
			$first = false;
		}
		return $list;
	}

	public function specie($id) {
		$specie_list = ORM::factory('Pet_Specie')->find_all();
		$species = array();

		foreach($specie_list as $s) {
			$species[] = array(
				'id' => $s->id,
				'value' => $s->name,
				'selected' => ($id == $s->id)
			);
		}

		return $species;
	}

	public function colour($specie, $id) {
		$colour_list = $specie->colours->find_all();
		$colours = array();

		foreach($colour_list as $c) {
			$colours[] = array(
				'id' => $c->id,
				'value' => $c->name,
				'selected' => ($id == $c->id)
			);
		}
		return $colours;
	}

	public function gender($type) {
		return array(
			array('id' => 'male','value' => 'Male', 'selected' => ($type == 'male')),
			array('id' => 'female', 'value' => 'Female', 'selected' => ($type == 'female'))
		);
	}

	public function csrf()
	{
		return Security::token();
	}

}
