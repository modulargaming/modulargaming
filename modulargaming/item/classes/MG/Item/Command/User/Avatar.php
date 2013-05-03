<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Give the player an avatar
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Item_Command_User_Avatar extends Item_Command {

	protected function _build($name)
	{
		return array(
			'title' => 'Avatar',
			'search' => 'avatar',
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-small search'
					)
				)
			)
		);
	}

	public function validate($param)
	{
		$avatar = ORM::factory('Avatar')
			->where('name', '=', $param)
			->find();

		return $avatar->loaded();
	}

	public function perform($item, $param, $data=null)
	{
		$avatar = ORM::factory('Avatar')
			->where('name', '=', $param)
			->find();

		$user = Auth::instance()->get_user();

		if($user->has('avatars', $avatar))
			return '';
		else {
			$user->add('avatars', $avatar);
			$user->save();
			return '<img src="'.URL::site($avatar->img()).'" /> You have recieved the "'.$avatar->title.'" avatar!';
		}
	}
}
