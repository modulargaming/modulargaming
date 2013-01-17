<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Profile extends Abstract_View {

        public $profile_user;
        public $pets;

        public function title()
        {
        	return $this->profile_user->username . '\'s Profile';
        }

        public function profile_user()
	{
		$profile_user = $this->profile_user;
		$profile_user = array(
			'id'      => $profile_user->id,
			'username'      => $profile_user->username,
			'created' => Date::format($profile_user->created),
			'last_login' => Date::format($profile_user->last_login),
			'post_count'    => $profile_user->post_count,
			'avatar'    => $profile_user->avatar,
			'title'    => $profile_user->title->title,
			'about'    => $profile_user->about,
		);

		return $profile_user;

	}

        public function pets()
        {
                $pets = $this->pets;
                $user_pets = array();
                foreach ($pets as $pet)
                {
                        $user_pets[] = array(
                                'src' => URL::base().'assets/img/pets/'.$pet->specie->id.'/'.$pet->colour->image,
                                'href' => Route::url('pet', array('name' => strtolower($pet->name))),
                                'name' => $pet->name,
                                'specie' => $pet->specie->name,
                                'colour' => $pet->colour->name,
                        );
                }
                return $user_pets;
        }


}
