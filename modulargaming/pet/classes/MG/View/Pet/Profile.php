<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for pet profile.
 *
 * @package    MG/Pet
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Pet_Profile extends Abstract_View {

	public $title = 'Pet name';

	public function pet()
	{
		$pet = $this->pet;

		return array(
			'id'         => $pet->id,
			'created'    => Date::format($pet->created),
			'name'       => $pet->name,
			'gender'     => $pet->gender,
			'specie'     => $pet->specie,
			'colour'     => $pet->colour,
			'img'        => $pet->img(),
			'hunger'     => $pet->hunger,
			'happiness'  => $pet->happiness,
			'user'       => array(
				'username' => $pet->user->username,
				'id'       => $pet->user->id,
				'href'     => Route::url('user.profile', array('id' => $pet->user->id))
			)
		);
	}

	protected function get_breadcrumb()
	{
		$array = array();

		if ($this->pet->user_id == $this->player())
		{
			$array[] = array(
				'title' => 'Your pets',
				'href'  => Route::url('pets')
			);
		}
		elseif ($this->pet->user_id)
		{
			$array[] = array(
				'title' => $this->pet->user->username . "'" . ($this->pet->user->username[strlen($this->pet->user->username)-1] == 's' ? '' : 's') . ' pets',
				'href'  => '#'
			);
		}
		else
		{
			$array[] = array(
				'title' => 'Abandoned pets',
				'href'  => Route::url('pets')
			);
		}

		$array[] = array(
			'title' => $this->pet->name,
			'href'  => Route::url('pet', array('name' => strtolower($this->pet->name)))
		);

		return array_merge(parent::get_breadcrumb(), $array);
	}
}
