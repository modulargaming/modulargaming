<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Profile extends View_Base {

	public $profile_user;

	public function title()
	{
		return $this->profile_user['username'] . '\'s Profile';
	}

        public function profile_user()
        {
                $profile_user = $this->profile_user;
                $profile_user['last_login'] = Date::format($profile_user['last_login']);
                $profile_user['created'] = Date::format($profile_user['created']);

                return $profile_user;
        }


}
