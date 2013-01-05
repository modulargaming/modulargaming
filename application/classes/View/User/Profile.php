<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Profile extends View_Base {

        public $profile_user;
        public $pets;

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

        public function pets()
        {
                $pets = $this->pets;
                $user_pets = array();
                foreach ($pets as $pet)
                {
                        $user_pets[] = array(
                                'src' => URL::base().'assets/img/pets/'.$pet->race->id.'/'.$pet->colour->image,
                                'href' => Route::url('pet', array('name' => strtolower($pet->name))),
                                'name' => $pet->name,
                                'race' => $pet->race->name,
                                'colour' => $pet->colour->name,
                        );
                }
                return $user_pets;
        }


}
