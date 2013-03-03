<?php defined('SYSPATH') OR die('No direct script access.');
 
class View_User_Settings_Profile extends Abstract_View {

	public function properties()
	{
		$user = Auth::instance()->get_user();
		return array(
			'about'     => $user->get_property('about'),
			'signature' => $user->get_property('signature')
		);
	}

}
