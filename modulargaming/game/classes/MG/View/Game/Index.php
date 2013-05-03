<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Game_Index extends Abstract_View_Game {

	public $title = 'Games';

	public function links()
	{
		return array(
			'rockpaperscissors' => Route::url('games.rock-paper-scissors'),
			'luckywheel' => Route::url('games.lucky-wheel')
		);
	}

}
