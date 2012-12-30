<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Profile extends View_Base {

	public $title = 'Pet name';

        public function pet()
        {
                $pet = $this->pet;
                $pet->created = Date::format($pet->created);

                return $pet;
        }


}
